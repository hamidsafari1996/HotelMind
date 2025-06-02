<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        $this->user = new User();
    }

    public function testImplementsCorrectInterfaces(): void
    {
        $this->assertInstanceOf(UserInterface::class, $this->user);
        $this->assertInstanceOf(PasswordAuthenticatedUserInterface::class, $this->user);
    }

    public function testConstructor(): void
    {
        $user = new User();
        
        $this->assertNull($user->getId());
        $this->assertNull($user->getUsername());
        $this->assertEquals(['ROLE_USER'], $user->setRoles([])->getRoles()); // Will still contain ROLE_USER
        $this->assertNull($user->getPassword());
    }

    public function testGetSetId(): void
    {
        // ID should be null initially (will be set by Doctrine)
        $this->assertNull($this->user->getId());
        
        // Use reflection to test ID setter (since it's typically handled by Doctrine)
        $reflection = new \ReflectionClass($this->user);
        $idProperty = $reflection->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($this->user, 123);
        
        $this->assertEquals(123, $this->user->getId());
    }

    public function testGetSetUsername(): void
    {
        $this->assertNull($this->user->getUsername());
        
        $result = $this->user->setUsername('testuser');
        
        // Test fluent interface
        $this->assertSame($this->user, $result);
        $this->assertEquals('testuser', $this->user->getUsername());
        
        // Test with different username
        $this->user->setUsername('anotheruser');
        $this->assertEquals('anotheruser', $this->user->getUsername());
    }

    public function testGetUserIdentifier(): void
    {
        // Test with null username
        $this->assertEquals('', $this->user->getUserIdentifier());
        
        // Test with username set
        $this->user->setUsername('testuser');
        $this->assertEquals('testuser', $this->user->getUserIdentifier());
        
        // Test with different username
        $this->user->setUsername('admin@example.com');
        $this->assertEquals('admin@example.com', $this->user->getUserIdentifier());
    }

    public function testGetSetRoles(): void
    {
        // Initially should return only ROLE_USER
        $this->assertEquals(['ROLE_USER'], $this->user->getRoles());
        
        // Test setting empty roles - should still contain ROLE_USER
        $result = $this->user->setRoles([]);
        $this->assertSame($this->user, $result); // Test fluent interface
        $this->assertEquals(['ROLE_USER'], $this->user->getRoles());
        
        // Test setting single role
        $this->user->setRoles(['ROLE_ADMIN']);
        $this->assertEquals(['ROLE_ADMIN', 'ROLE_USER'], $this->user->getRoles());
        
        // Test setting multiple roles
        $this->user->setRoles(['ROLE_ADMIN', 'ROLE_MODERATOR']);
        $expectedRoles = ['ROLE_ADMIN', 'ROLE_MODERATOR', 'ROLE_USER'];
        $this->assertEquals($expectedRoles, $this->user->getRoles());
    }

    public function testGetRolesWithDuplicates(): void
    {
        // Test that duplicate roles are removed
        $this->user->setRoles(['ROLE_ADMIN', 'ROLE_USER', 'ROLE_ADMIN']);
        $roles = $this->user->getRoles();
        
        // Should contain each role only once
        $this->assertContains('ROLE_ADMIN', $roles);
        $this->assertContains('ROLE_USER', $roles);
        $this->assertEquals(2, count($roles));
        $this->assertEquals(['ROLE_ADMIN', 'ROLE_USER'], array_values($roles));
    }

    public function testGetRolesAlwaysContainsUserRole(): void
    {
        // Test various role combinations to ensure ROLE_USER is always present
        $testCases = [
            [],
            ['ROLE_ADMIN'],
            ['ROLE_MODERATOR', 'ROLE_EDITOR'],
            ['ROLE_SUPER_ADMIN', 'ROLE_ADMIN', 'ROLE_MODERATOR']
        ];
        
        foreach ($testCases as $roles) {
            $this->user->setRoles($roles);
            $userRoles = $this->user->getRoles();
            
            $this->assertContains('ROLE_USER', $userRoles, 
                'ROLE_USER should always be present in roles: ' . implode(', ', $roles));
        }
    }

    public function testGetSetPassword(): void
    {
        $this->assertNull($this->user->getPassword());
        
        $result = $this->user->setPassword('hashedPassword123');
        
        // Test fluent interface
        $this->assertSame($this->user, $result);
        $this->assertEquals('hashedPassword123', $this->user->getPassword());
        
        // Test with different password
        $this->user->setPassword('anotherHashedPassword');
        $this->assertEquals('anotherHashedPassword', $this->user->getPassword());
    }

    public function testEraseCredentials(): void
    {
        // Set up user with data
        $this->user->setUsername('testuser');
        $this->user->setPassword('hashedPassword');
        $this->user->setRoles(['ROLE_ADMIN']);
        
        // Call eraseCredentials
        $this->user->eraseCredentials();
        
        // Verify that main credentials are not affected (as per current implementation)
        $this->assertEquals('testuser', $this->user->getUsername());
        $this->assertEquals('hashedPassword', $this->user->getPassword());
        $this->assertContains('ROLE_ADMIN', $this->user->getRoles());
        
        // Note: The current implementation doesn't clear anything,
        // but this test ensures the method exists and doesn't break anything
    }

    public function testFluentInterface(): void
    {
        // Test chaining all setters
        $result = $this->user
            ->setUsername('fluentuser')
            ->setPassword('fluentpassword')
            ->setRoles(['ROLE_ADMIN', 'ROLE_MODERATOR']);
        
        // All methods should return the same user instance
        $this->assertSame($this->user, $result);
        
        // Verify all values were set
        $this->assertEquals('fluentuser', $this->user->getUsername());
        $this->assertEquals('fluentpassword', $this->user->getPassword());
        $this->assertContains('ROLE_ADMIN', $this->user->getRoles());
        $this->assertContains('ROLE_MODERATOR', $this->user->getRoles());
        $this->assertContains('ROLE_USER', $this->user->getRoles());
    }

    public function testValidDataTypes(): void
    {
        $this->user->setUsername('typetest');
        $this->user->setPassword('password123');
        $this->user->setRoles(['ROLE_ADMIN']);
        
        // Verify data types
        $this->assertIsString($this->user->getUsername());
        $this->assertIsString($this->user->getUserIdentifier());
        $this->assertIsArray($this->user->getRoles());
        $this->assertIsString($this->user->getPassword());
        
        // Verify that all roles are strings
        foreach ($this->user->getRoles() as $role) {
            $this->assertIsString($role);
        }
    }

    public function testEdgeCases(): void
    {
        // Test with empty string username
        $this->user->setUsername('');
        $this->assertEquals('', $this->user->getUsername());
        $this->assertEquals('', $this->user->getUserIdentifier());
        
        // Test with special characters in username
        $this->user->setUsername('user@example.com');
        $this->assertEquals('user@example.com', $this->user->getUsername());
        $this->assertEquals('user@example.com', $this->user->getUserIdentifier());
        
        // Test with empty string password
        $this->user->setPassword('');
        $this->assertEquals('', $this->user->getPassword());
        
        // Test with very long role names
        $longRole = 'ROLE_' . str_repeat('VERY_LONG_ROLE_NAME_', 10);
        $this->user->setRoles([$longRole]);
        $this->assertContains($longRole, $this->user->getRoles());
    }

    public function testCompleteUserScenario(): void
    {
        // Simulate a complete user setup
        $this->user
            ->setUsername('admin@company.com')
            ->setPassword('$2y$13$hashedPasswordExample')
            ->setRoles(['ROLE_ADMIN', 'ROLE_SUPER_ADMIN']);
        
        // Use reflection to set ID (simulating Doctrine behavior)
        $reflection = new \ReflectionClass($this->user);
        $idProperty = $reflection->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($this->user, 1);
        
        // Verify complete user state
        $this->assertEquals(1, $this->user->getId());
        $this->assertEquals('admin@company.com', $this->user->getUsername());
        $this->assertEquals('admin@company.com', $this->user->getUserIdentifier());
        $this->assertEquals('$2y$13$hashedPasswordExample', $this->user->getPassword());
        
        $roles = $this->user->getRoles();
        $this->assertContains('ROLE_ADMIN', $roles);
        $this->assertContains('ROLE_SUPER_ADMIN', $roles);
        $this->assertContains('ROLE_USER', $roles);
        $this->assertEquals(3, count($roles));
    }

    public function testRolesArrayIntegrity(): void
    {
        // Test that the roles array maintains its integrity
        $originalRoles = ['ROLE_ADMIN', 'ROLE_MODERATOR'];
        $this->user->setRoles($originalRoles);
        
        // Get roles and modify the returned array
        $returnedRoles = $this->user->getRoles();
        $returnedRoles[] = 'ROLE_HACKER'; // Try to modify
        
        // Verify that the user's internal roles are not affected
        $freshRoles = $this->user->getRoles();
        $this->assertNotContains('ROLE_HACKER', $freshRoles);
        $this->assertContains('ROLE_ADMIN', $freshRoles);
        $this->assertContains('ROLE_MODERATOR', $freshRoles);
        $this->assertContains('ROLE_USER', $freshRoles);
    }
} 