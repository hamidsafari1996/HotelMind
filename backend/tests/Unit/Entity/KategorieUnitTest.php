<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Kategorie;
use App\Entity\Hotel;
use PHPUnit\Framework\TestCase;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class KategorieUnitTest extends TestCase
{
    private Kategorie $kategorie;

    protected function setUp(): void
    {
        $this->kategorie = new Kategorie();
    }

    public function testEntityCanBeInstantiated(): void
    {
        // Test that Kategorie can be instantiated
        $kategorie = new Kategorie();
        $this->assertInstanceOf(Kategorie::class, $kategorie);
    }

    public function testIdIsNullByDefault(): void
    {
        // Test that ID is null by default (will be set by Doctrine)
        $this->assertNull($this->kategorie->getId());
    }

    public function testNameIsNullByDefault(): void
    {
        // Test that name is null by default
        $this->assertNull($this->kategorie->getName());
    }

    public function testHotelsCollectionIsInitializedInConstructor(): void
    {
        // Test that hotels collection is initialized as ArrayCollection
        $hotels = $this->kategorie->getHotels();
        $this->assertInstanceOf(ArrayCollection::class, $hotels);
        $this->assertInstanceOf(Collection::class, $hotels);
        $this->assertCount(0, $hotels);
        $this->assertTrue($hotels->isEmpty());
    }

    public function testSetAndGetName(): void
    {
        // Test setting and getting name
        $name = 'Luxury Hotels';
        $result = $this->kategorie->setName($name);
        
        // Assert fluent interface
        $this->assertSame($this->kategorie, $result);
        // Assert name is set correctly
        $this->assertEquals($name, $this->kategorie->getName());
    }

    public function testSetNameWithDifferentValues(): void
    {
        // Test with various name values
        $testNames = [
            'Budget Hotels',
            'Boutique Hotels',
            'Business Hotels',
            'Resort Hotels',
            '5-Star Luxury',
            'Hotel & Spa'
        ];

        foreach ($testNames as $name) {
            $this->kategorie->setName($name);
            $this->assertEquals($name, $this->kategorie->getName());
        }
    }

    public function testAddHotel(): void
    {
        // Create a mock hotel
        $hotel = $this->createMock(Hotel::class);
        
        // Mock hotel's setKategorie method to be called with this kategorie
        $hotel->expects($this->once())
            ->method('setKategorie')
            ->with($this->kategorie)
            ->willReturnSelf();
        
        // Add hotel to kategorie
        $result = $this->kategorie->addHotel($hotel);
        
        // Assert fluent interface
        $this->assertSame($this->kategorie, $result);
        
        // Assert hotel is added to collection
        $hotels = $this->kategorie->getHotels();
        $this->assertCount(1, $hotels);
        $this->assertTrue($hotels->contains($hotel));
    }

    public function testAddSameHotelTwiceDoesNotDuplicate(): void
    {
        // Create a mock hotel
        $hotel = $this->createMockHotel();
        
        // Mock the contains method to return false first time, true second time
        $hotels = $this->createMock(ArrayCollection::class);
        $hotels->expects($this->exactly(2))
            ->method('contains')
            ->with($hotel)
            ->willReturnOnConsecutiveCalls(false, true);
        
        $hotels->expects($this->once())
            ->method('add')
            ->with($hotel);
        
        // Use reflection to set the hotels collection
        $reflection = new \ReflectionClass($this->kategorie);
        $hotelsProperty = $reflection->getProperty('hotels');
        $hotelsProperty->setAccessible(true);
        $hotelsProperty->setValue($this->kategorie, $hotels);
        
        // Add hotel twice
        $this->kategorie->addHotel($hotel);
        $this->kategorie->addHotel($hotel); // Should not add again
    }

    public function testRemoveHotel(): void
    {
        // Create a mock hotel
        $hotel = $this->createMockHotel();
        
        // Mock hotel's getKategorie method to return this kategorie
        $hotel->expects($this->once())
            ->method('getKategorie')
            ->willReturn($this->kategorie);
        
        // Mock hotel's setKategorie method to be called with null
        $hotel->expects($this->once())
            ->method('setKategorie')
            ->with(null);
        
        // Mock the collection's removeElement method
        $hotels = $this->createMock(ArrayCollection::class);
        $hotels->expects($this->once())
            ->method('removeElement')
            ->with($hotel)
            ->willReturn(true);
        
        // Use reflection to set the hotels collection
        $reflection = new \ReflectionClass($this->kategorie);
        $hotelsProperty = $reflection->getProperty('hotels');
        $hotelsProperty->setAccessible(true);
        $hotelsProperty->setValue($this->kategorie, $hotels);
        
        // Remove hotel
        $result = $this->kategorie->removeHotel($hotel);
        
        // Assert fluent interface
        $this->assertSame($this->kategorie, $result);
    }

    public function testRemoveHotelThatDoesNotBelongToKategorie(): void
    {
        // Create a mock hotel that belongs to a different kategorie
        $hotel = $this->createMockHotel();
        $otherKategorie = new Kategorie();
        
        // Mock hotel's getKategorie method to return different kategorie
        $hotel->expects($this->once())
            ->method('getKategorie')
            ->willReturn($otherKategorie);
        
        // Mock hotel's setKategorie should NOT be called
        $hotel->expects($this->never())
            ->method('setKategorie');
        
        // Mock the collection's removeElement method
        $hotels = $this->createMock(ArrayCollection::class);
        $hotels->expects($this->once())
            ->method('removeElement')
            ->with($hotel)
            ->willReturn(true);
        
        // Use reflection to set the hotels collection
        $reflection = new \ReflectionClass($this->kategorie);
        $hotelsProperty = $reflection->getProperty('hotels');
        $hotelsProperty->setAccessible(true);
        $hotelsProperty->setValue($this->kategorie, $hotels);
        
        // Remove hotel
        $this->kategorie->removeHotel($hotel);
    }

    public function testRemoveHotelNotInCollection(): void
    {
        // Create a mock hotel
        $hotel = $this->createMockHotel();
        
        // Mock the collection's removeElement method to return false
        $hotels = $this->createMock(ArrayCollection::class);
        $hotels->expects($this->once())
            ->method('removeElement')
            ->with($hotel)
            ->willReturn(false);
        
        // Hotel's getKategorie should not be called if not in collection
        $hotel->expects($this->never())
            ->method('getKategorie');
        
        // Use reflection to set the hotels collection
        $reflection = new \ReflectionClass($this->kategorie);
        $hotelsProperty = $reflection->getProperty('hotels');
        $hotelsProperty->setAccessible(true);
        $hotelsProperty->setValue($this->kategorie, $hotels);
        
        // Remove hotel
        $result = $this->kategorie->removeHotel($hotel);
        
        // Should still return itself for fluent interface
        $this->assertSame($this->kategorie, $result);
    }

    public function testToStringWithName(): void
    {
        // Test __toString with name set
        $name = 'Luxury Hotels';
        $this->kategorie->setName($name);
        
        $this->assertEquals($name, (string) $this->kategorie);
        $this->assertEquals($name, $this->kategorie->__toString());
    }

    public function testToStringWithoutName(): void
    {
        // Test __toString without name set (should return empty string)
        $this->assertEquals('', (string) $this->kategorie);
        $this->assertEquals('', $this->kategorie->__toString());
    }

    public function testToStringWithNullName(): void
    {
        // Explicitly test with null name
        $this->kategorie->setName('Test');
        
        // Use reflection to set name to null
        $reflection = new \ReflectionClass($this->kategorie);
        $nameProperty = $reflection->getProperty('name');
        $nameProperty->setAccessible(true);
        $nameProperty->setValue($this->kategorie, null);
        
        $this->assertEquals('', (string) $this->kategorie);
    }

    public function testImplementsJsonSerializable(): void
    {
        // Test that Kategorie implements JsonSerializable
        $this->assertInstanceOf(\JsonSerializable::class, $this->kategorie);
    }

    public function testJsonSerializeWithData(): void
    {
        // Set up kategorie with data
        $this->kategorie->setName('Premium Hotels');
        
        // Use reflection to set ID (normally set by Doctrine)
        $this->setKategorieId(123);
        
        // Test JSON serialization
        $expected = [
            'id' => 123,
            'name' => 'Premium Hotels'
        ];
        
        $this->assertEquals($expected, $this->kategorie->jsonSerialize());
        $this->assertEquals(json_encode($expected), json_encode($this->kategorie));
    }

    public function testJsonSerializeWithNullValues(): void
    {
        // Test JSON serialization with null values
        $expected = [
            'id' => null,
            'name' => null
        ];
        
        $this->assertEquals($expected, $this->kategorie->jsonSerialize());
    }

    public function testJsonSerializeWithPartialData(): void
    {
        // Test with only name set
        $this->kategorie->setName('Budget Hotels');
        
        $expected = [
            'id' => null,
            'name' => 'Budget Hotels'
        ];
        
        $this->assertEquals($expected, $this->kategorie->jsonSerialize());
    }

    public function testGetHotelsReturnsCollection(): void
    {
        // Test that getHotels returns the hotels collection
        $hotels = $this->kategorie->getHotels();
        $this->assertInstanceOf(Collection::class, $hotels);
        
        // Initially should be empty
        $this->assertCount(0, $hotels);
    }

    public function testHotelManagementWorkflow(): void
    {
        // Test complete workflow of adding and removing hotels
        $hotel1 = $this->createMockHotel();
        $hotel2 = $this->createMockHotel();
        
        // Initially no hotels
        $this->assertCount(0, $this->kategorie->getHotels());
        
        // Add hotels using real implementation
        $kategorie = new Kategorie(); // Use real instance for this test
        $realHotel1 = new Hotel();
        $realHotel2 = new Hotel();
        
        $kategorie->addHotel($realHotel1);
        $this->assertCount(1, $kategorie->getHotels());
        $this->assertTrue($kategorie->getHotels()->contains($realHotel1));
        
        $kategorie->addHotel($realHotel2);
        $this->assertCount(2, $kategorie->getHotels());
        $this->assertTrue($kategorie->getHotels()->contains($realHotel2));
        
        // Remove one hotel
        $kategorie->removeHotel($realHotel1);
        $this->assertCount(1, $kategorie->getHotels());
        $this->assertFalse($kategorie->getHotels()->contains($realHotel1));
        $this->assertTrue($kategorie->getHotels()->contains($realHotel2));
    }

    public function testFluentInterfaceForAllSetters(): void
    {
        // Test that all setter methods return $this for fluent interface
        $name = 'Test Category';
        $hotel = new Hotel();
        
        $result1 = $this->kategorie->setName($name);
        $result2 = $this->kategorie->addHotel($hotel);
        $result3 = $this->kategorie->removeHotel($hotel);
        
        $this->assertSame($this->kategorie, $result1);
        $this->assertSame($this->kategorie, $result2);
        $this->assertSame($this->kategorie, $result3);
    }

    public function testEntityMethodsExist(): void
    {
        // Test that all expected methods exist
        $expectedMethods = [
            'getId', 'getName', 'setName', 'getHotels', 
            'addHotel', 'removeHotel', '__toString', 'jsonSerialize'
        ];
        
        foreach ($expectedMethods as $method) {
            $this->assertTrue(
                method_exists($this->kategorie, $method),
                "Method {$method} should exist"
            );
        }
    }

    public function testConstructorInitializesHotelsCollection(): void
    {
        // Test that constructor properly initializes the hotels collection
        $newKategorie = new Kategorie();
        $hotels = $newKategorie->getHotels();
        
        $this->assertInstanceOf(ArrayCollection::class, $hotels);
        $this->assertCount(0, $hotels);
        $this->assertTrue($hotels->isEmpty());
    }

    /**
     * Helper method to create a mock Hotel
     */
    private function createMockHotel(): Hotel
    {
        $hotel = $this->createMock(Hotel::class);
        $hotel->method('setKategorie')->willReturnSelf();
        return $hotel;
    }

    /**
     * Helper method to set kategorie ID using reflection
     */
    private function setKategorieId(int $id): void
    {
        $reflection = new \ReflectionClass($this->kategorie);
        $idProperty = $reflection->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($this->kategorie, $id);
    }
} 