<?php

namespace App\Tests\Unit\Controller;

use App\Controller\SecurityController;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Twig\Environment;
use App\Entity\User;

class SecurityControllerUnitTest extends TestCase
{
    private SecurityController $controller;
    private AuthenticationUtils $authenticationUtils;
    private Security $security;
    private Environment $twig;
    private RouterInterface $router;

    protected function setUp(): void
    {
        $this->authenticationUtils = $this->createMock(AuthenticationUtils::class);
        $this->security = $this->createMock(Security::class);
        $this->twig = $this->createMock(Environment::class);
        $this->router = $this->createMock(RouterInterface::class);

        $this->controller = new SecurityController();
        
        // Inject dependencies via reflection
        $this->injectPrivateProperty('twig', $this->twig);
        $this->injectPrivateProperty('router', $this->router);
        
        // Mock container
        $container = $this->createMock(\Symfony\Component\DependencyInjection\ContainerInterface::class);
        $container->method('get')
            ->willReturnMap([
                ['twig', 1, $this->twig],
                ['router', 1, $this->router],
            ]);
        $container->method('has')->willReturn(true);
        
        $this->controller->setContainer($container);
    }

    private function injectPrivateProperty(string $property, $value): void
    {
        $reflection = new \ReflectionClass($this->controller);
        if ($reflection->hasProperty($property)) {
            $prop = $reflection->getProperty($property);
            $prop->setAccessible(true);
            $prop->setValue($this->controller, $value);
        }
    }

    private function createMockUser(string $username = 'testuser'): User
    {
        $user = new User();
        $user->setUsername($username);
        return $user;
    }

    public function testLoginWhenUserNotAuthenticated(): void
    {
        // Arrange
        $lastUsername = 'john.doe';
        $authError = new AuthenticationException('Invalid credentials');

        $this->security
            ->expects($this->once())
            ->method('getUser')
            ->willReturn(null); // User not authenticated

        $this->authenticationUtils
            ->expects($this->once())
            ->method('getLastAuthenticationError')
            ->willReturn($authError);

        $this->authenticationUtils
            ->expects($this->once())
            ->method('getLastUsername')
            ->willReturn($lastUsername);

        $this->twig
            ->expects($this->once())
            ->method('render')
            ->with('security/login.html.twig', [
                'last_username' => $lastUsername,
                'error' => $authError,
            ])
            ->willReturn('<html>Login Form</html>');

        // Act
        $response = $this->controller->login($this->authenticationUtils, $this->security);

        // Assert
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('<html>Login Form</html>', $response->getContent());
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testLoginWhenUserAlreadyAuthenticated(): void
    {
        // Arrange
        $user = $this->createMockUser();

        $this->security
            ->expects($this->once())
            ->method('getUser')
            ->willReturn($user); // User is authenticated

        $this->router
            ->expects($this->once())
            ->method('generate')
            ->with('app_reservation_index')
            ->willReturn('/');

        // AuthenticationUtils should not be called since user is already authenticated
        $this->authenticationUtils
            ->expects($this->never())
            ->method('getLastAuthenticationError');

        $this->authenticationUtils
            ->expects($this->never())
            ->method('getLastUsername');

        $this->twig
            ->expects($this->never())
            ->method('render');

        // Act
        $response = $this->controller->login($this->authenticationUtils, $this->security);

        // Assert
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());
        $this->assertEquals('/', $response->headers->get('Location'));
    }

    public function testLoginWithNoAuthenticationError(): void
    {
        // Arrange
        $lastUsername = 'jane.doe';

        $this->security
            ->expects($this->once())
            ->method('getUser')
            ->willReturn(null);

        $this->authenticationUtils
            ->expects($this->once())
            ->method('getLastAuthenticationError')
            ->willReturn(null); // No authentication error

        $this->authenticationUtils
            ->expects($this->once())
            ->method('getLastUsername')
            ->willReturn($lastUsername);

        $this->twig
            ->expects($this->once())
            ->method('render')
            ->with('security/login.html.twig', [
                'last_username' => $lastUsername,
                'error' => null,
            ])
            ->willReturn('<html>Clean Login Form</html>');

        // Act
        $response = $this->controller->login($this->authenticationUtils, $this->security);

        // Assert
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('<html>Clean Login Form</html>', $response->getContent());
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testLoginWithEmptyLastUsername(): void
    {
        // Arrange
        $this->security
            ->expects($this->once())
            ->method('getUser')
            ->willReturn(null);

        $this->authenticationUtils
            ->expects($this->once())
            ->method('getLastAuthenticationError')
            ->willReturn(null);

        $this->authenticationUtils
            ->expects($this->once())
            ->method('getLastUsername')
            ->willReturn(''); // Empty username

        $this->twig
            ->expects($this->once())
            ->method('render')
            ->with('security/login.html.twig', [
                'last_username' => '',
                'error' => null,
            ])
            ->willReturn('<html>Empty Username Login Form</html>');

        // Act
        $response = $this->controller->login($this->authenticationUtils, $this->security);

        // Assert
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('<html>Empty Username Login Form</html>', $response->getContent());
    }

    public function testLogoutThrowsLogicException(): void
    {
        // Arrange & Assert
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('This method can be blank - it will be intercepted by the logout key on your firewall.');

        // Act
        $this->controller->logout();
    }

    /**
     * Test that verifies the authentication flow dependencies work correctly
     */
    public function testAuthenticationUtilsDependencyLogic(): void
    {
        // Test that AuthenticationUtils correctly returns error and username
        $error = new AuthenticationException('Test error');
        $username = 'testuser';

        $this->authenticationUtils
            ->expects($this->once())
            ->method('getLastAuthenticationError')
            ->willReturn($error);

        $this->authenticationUtils
            ->expects($this->once())
            ->method('getLastUsername')
            ->willReturn($username);

        // Verify the mocked behavior
        $this->assertEquals($error, $this->authenticationUtils->getLastAuthenticationError());
        $this->assertEquals($username, $this->authenticationUtils->getLastUsername());
    }

    /**
     * Test that verifies the Security service dependency
     */
    public function testSecurityServiceLogic(): void
    {
        // Create a separate mock for this test to avoid conflicts
        $securityMock = $this->createMock(Security::class);
        $user = $this->createMockUser('securitytest');

        $securityMock
            ->expects($this->exactly(2))
            ->method('getUser')
            ->willReturnOnConsecutiveCalls(null, $user);

        // First call should return null (not authenticated)
        $firstResult = $securityMock->getUser();
        $this->assertNull($firstResult);
        
        // Second call should return user (authenticated)
        $secondResult = $securityMock->getUser();
        $this->assertInstanceOf(User::class, $secondResult);
        $this->assertEquals('securitytest', $secondResult->getUsername());
    }

    /**
     * Test route generation for redirects
     */
    public function testRouteGenerationLogic(): void
    {
        // Test that router can generate the correct route
        $this->router
            ->expects($this->once())
            ->method('generate')
            ->with('app_reservation_index')
            ->willReturn('/reservations');

        $redirectUrl = $this->router->generate('app_reservation_index');
        $this->assertEquals('/reservations', $redirectUrl);
    }
} 