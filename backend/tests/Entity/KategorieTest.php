<?php

namespace App\Tests\Entity;

use App\Entity\Kategorie;
use App\Entity\Hotel;
use PHPUnit\Framework\TestCase;
use Doctrine\Common\Collections\ArrayCollection;

class KategorieTest extends TestCase
{
    private Kategorie $kategorie;

    protected function setUp(): void
    {
        $this->kategorie = new Kategorie();
    }

    public function testConstructor(): void
    {
        $kategorie = new Kategorie();
        
        $this->assertNull($kategorie->getId());
        $this->assertNull($kategorie->getName());
        $this->assertInstanceOf(ArrayCollection::class, $kategorie->getHotels());
        $this->assertCount(0, $kategorie->getHotels());
    }

    public function testGetSetId(): void
    {
        // ID should be null initially (will be set by Doctrine)
        $this->assertNull($this->kategorie->getId());
        
        // Use reflection to test ID setter (since it's typically handled by Doctrine)
        $reflection = new \ReflectionClass($this->kategorie);
        $idProperty = $reflection->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($this->kategorie, 123);
        
        $this->assertEquals(123, $this->kategorie->getId());
    }

    public function testGetSetName(): void
    {
        // Test initial state
        $this->assertNull($this->kategorie->getName());
        
        // Test setter and getter
        $result = $this->kategorie->setName('Test Kategorie');
        
        // Test fluent interface
        $this->assertSame($this->kategorie, $result);
        $this->assertEquals('Test Kategorie', $this->kategorie->getName());
        
        // Test setting different name
        $this->kategorie->setName('Another Kategorie');
        $this->assertEquals('Another Kategorie', $this->kategorie->getName());
    }

    public function testGetHotels(): void
    {
        $hotels = $this->kategorie->getHotels();
        
        $this->assertInstanceOf(ArrayCollection::class, $hotels);
        $this->assertCount(0, $hotels);
    }

    public function testAddHotel(): void
    {
        $hotel = $this->createMock(Hotel::class);
        $hotel->expects($this->once())
            ->method('setKategorie')
            ->with($this->kategorie);
        
        // Test adding hotel
        $result = $this->kategorie->addHotel($hotel);
        
        // Test fluent interface
        $this->assertSame($this->kategorie, $result);
        
        // Test hotel was added
        $this->assertCount(1, $this->kategorie->getHotels());
        $this->assertTrue($this->kategorie->getHotels()->contains($hotel));
    }

    public function testAddSameHotelTwice(): void
    {
        $hotel = $this->createMock(Hotel::class);
        $hotel->expects($this->once()) // Should only be called once
            ->method('setKategorie')
            ->with($this->kategorie);
        
        // Add hotel twice
        $this->kategorie->addHotel($hotel);
        $this->kategorie->addHotel($hotel);
        
        // Should only be added once
        $this->assertCount(1, $this->kategorie->getHotels());
    }

    public function testRemoveHotel(): void
    {
        $hotel = $this->createMock(Hotel::class);
        
        // Setup hotel mock to return this kategorie when getKategorie is called
        $hotel->method('getKategorie')
            ->willReturn($this->kategorie);
        
        // Expect setKategorie to be called once during add and once during remove
        $hotel->expects($this->exactly(2))
            ->method('setKategorie')
            ->withConsecutive([$this->kategorie], [null]);
        
        // Add hotel first
        $this->kategorie->addHotel($hotel);
        $this->assertCount(1, $this->kategorie->getHotels());
        
        // Remove hotel
        $result = $this->kategorie->removeHotel($hotel);
        
        // Test fluent interface
        $this->assertSame($this->kategorie, $result);
        
        // Test hotel was removed
        $this->assertCount(0, $this->kategorie->getHotels());
        $this->assertFalse($this->kategorie->getHotels()->contains($hotel));
    }

    public function testRemoveHotelNotBelongingToKategorie(): void
    {
        $hotel = $this->createMock(Hotel::class);
        $otherKategorie = new Kategorie();
        
        // Setup hotel mock to return different kategorie
        $hotel->method('getKategorie')
            ->willReturn($otherKategorie);
        
        // Hotel should be added first (setKategorie called once during add)
        $hotel->expects($this->once())
            ->method('setKategorie')
            ->with($this->kategorie);
        
        // Add hotel first
        $this->kategorie->addHotel($hotel);
        
        // Remove hotel - should not set kategorie to null since getKategorie returns different kategorie
        $this->kategorie->removeHotel($hotel);
    }

    public function testRemoveNonExistentHotel(): void
    {
        $hotel = $this->createMock(Hotel::class);
        
        // Should not call setKategorie since hotel is not in collection
        $hotel->expects($this->never())
            ->method('setKategorie');
        
        // Try to remove hotel that was never added
        $result = $this->kategorie->removeHotel($hotel);
        
        // Should still return the kategorie (fluent interface)
        $this->assertSame($this->kategorie, $result);
        $this->assertCount(0, $this->kategorie->getHotels());
    }

    public function testToString(): void
    {
        // Test with null name - should return empty string, not null
        $this->assertEquals('', $this->kategorie->__toString());
        
        // Test with name set
        $this->kategorie->setName('Test Kategorie');
        $this->assertEquals('Test Kategorie', $this->kategorie->__toString());
        
        // Test with different name
        $this->kategorie->setName('Another Name');
        $this->assertEquals('Another Name', $this->kategorie->__toString());
    }

    public function testJsonSerialize(): void
    {
        // Test with ID and name set
        $reflection = new \ReflectionClass($this->kategorie);
        $idProperty = $reflection->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($this->kategorie, 42);
        
        $this->kategorie->setName('Test Kategorie');
        
        $expected = [
            'id' => 42,
            'name' => 'Test Kategorie'
        ];
        
        $this->assertEquals($expected, $this->kategorie->jsonSerialize());
    }

    public function testJsonSerializeWithNullValues(): void
    {
        // Test with null values
        $expected = [
            'id' => null,
            'name' => null
        ];
        
        $this->assertEquals($expected, $this->kategorie->jsonSerialize());
    }

    public function testHotelCollectionManagement(): void
    {
        $hotel1 = $this->createMock(Hotel::class);
        $hotel2 = $this->createMock(Hotel::class);
        $hotel3 = $this->createMock(Hotel::class);
        
        // Setup mocks - each hotel should return this kategorie when asked
        $hotel1->method('getKategorie')->willReturn($this->kategorie);
        $hotel2->method('getKategorie')->willReturn($this->kategorie);
        $hotel3->method('getKategorie')->willReturn($this->kategorie);
        
        // Each hotel should have setKategorie called once during add
        $hotel1->expects($this->once())->method('setKategorie')->with($this->kategorie);
        $hotel2->expects($this->exactly(2))->method('setKategorie')
            ->withConsecutive([$this->kategorie], [null]);
        $hotel3->expects($this->once())->method('setKategorie')->with($this->kategorie);
        
        // Add multiple hotels
        $this->kategorie->addHotel($hotel1);
        $this->kategorie->addHotel($hotel2);
        $this->kategorie->addHotel($hotel3);
        
        $this->assertCount(3, $this->kategorie->getHotels());
        $this->assertTrue($this->kategorie->getHotels()->contains($hotel1));
        $this->assertTrue($this->kategorie->getHotels()->contains($hotel2));
        $this->assertTrue($this->kategorie->getHotels()->contains($hotel3));
        
        // Remove one hotel (hotel2 setKategorie expectation is already set above)
        $this->kategorie->removeHotel($hotel2);
        
        $this->assertCount(2, $this->kategorie->getHotels());
        $this->assertTrue($this->kategorie->getHotels()->contains($hotel1));
        $this->assertFalse($this->kategorie->getHotels()->contains($hotel2));
        $this->assertTrue($this->kategorie->getHotels()->contains($hotel3));
    }
} 