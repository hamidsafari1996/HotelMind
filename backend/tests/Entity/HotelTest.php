<?php

namespace App\Tests\Entity;

use App\Entity\Hotel;
use App\Entity\Kategorie;
use PHPUnit\Framework\TestCase;

class HotelTest extends TestCase
{
    private Hotel $hotel;

    protected function setUp(): void
    {
        $this->hotel = new Hotel();
    }

    public function testConstructor(): void
    {
        $hotel = new Hotel();
        
        $this->assertNull($hotel->getId());
        $this->assertNull($hotel->getTitle());
        $this->assertNull($hotel->getLocation());
        $this->assertNull($hotel->getImage());
        $this->assertNull($hotel->getPrice());
        $this->assertNull($hotel->getDays());
        $this->assertNull($hotel->getPerson());
        $this->assertNull($hotel->getInfo());
        $this->assertNull($hotel->getDescription());
        $this->assertNull($hotel->getCreatedAt());
        $this->assertNull($hotel->getKategorie());
        $this->assertNull($hotel->getRating());
        $this->assertNull($hotel->getStars());
    }

    public function testGetSetId(): void
    {
        // ID should be null initially (will be set by Doctrine)
        $this->assertNull($this->hotel->getId());
        
        // Use reflection to test ID setter (since it's typically handled by Doctrine)
        $reflection = new \ReflectionClass($this->hotel);
        $idProperty = $reflection->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($this->hotel, 123);
        
        $this->assertEquals(123, $this->hotel->getId());
    }

    public function testGetSetTitle(): void
    {
        $this->assertNull($this->hotel->getTitle());
        
        $result = $this->hotel->setTitle('Test Hotel');
        
        // Test fluent interface
        $this->assertSame($this->hotel, $result);
        $this->assertEquals('Test Hotel', $this->hotel->getTitle());
        
        // Test with different title
        $this->hotel->setTitle('Another Hotel');
        $this->assertEquals('Another Hotel', $this->hotel->getTitle());
    }

    public function testGetSetLocation(): void
    {
        $this->assertNull($this->hotel->getLocation());
        
        $result = $this->hotel->setLocation('Test Location');
        
        // Test fluent interface
        $this->assertSame($this->hotel, $result);
        $this->assertEquals('Test Location', $this->hotel->getLocation());
    }

    public function testGetSetImage(): void
    {
        $this->assertNull($this->hotel->getImage());
        
        $result = $this->hotel->setImage('test-image.jpg');
        
        // Test fluent interface
        $this->assertSame($this->hotel, $result);
        $this->assertEquals('test-image.jpg', $this->hotel->getImage());
    }

    public function testGetSetPrice(): void
    {
        $this->assertNull($this->hotel->getPrice());
        
        $result = $this->hotel->setPrice('199.99');
        
        // Test fluent interface
        $this->assertSame($this->hotel, $result);
        $this->assertEquals('199.99', $this->hotel->getPrice());
        
        // Test with different price
        $this->hotel->setPrice('99.50');
        $this->assertEquals('99.50', $this->hotel->getPrice());
    }

    public function testGetSetDays(): void
    {
        $this->assertNull($this->hotel->getDays());
        
        $result = $this->hotel->setDays(7);
        
        // Test fluent interface
        $this->assertSame($this->hotel, $result);
        $this->assertEquals(7, $this->hotel->getDays());
    }

    public function testGetSetPerson(): void
    {
        $this->assertNull($this->hotel->getPerson());
        
        $result = $this->hotel->setPerson(2);
        
        // Test fluent interface
        $this->assertSame($this->hotel, $result);
        $this->assertEquals(2, $this->hotel->getPerson());
    }

    public function testGetSetInfo(): void
    {
        $this->assertNull($this->hotel->getInfo());
        
        $result = $this->hotel->setInfo('Test Info');
        
        // Test fluent interface
        $this->assertSame($this->hotel, $result);
        $this->assertEquals('Test Info', $this->hotel->getInfo());
    }

    public function testGetSetDescription(): void
    {
        $this->assertNull($this->hotel->getDescription());
        
        $result = $this->hotel->setDescription('Test Description');
        
        // Test fluent interface
        $this->assertSame($this->hotel, $result);
        $this->assertEquals('Test Description', $this->hotel->getDescription());
    }

    public function testGetSetCreatedAt(): void
    {
        $this->assertNull($this->hotel->getCreatedAt());
        
        $date = new \DateTimeImmutable('2024-01-01 12:00:00');
        $result = $this->hotel->setCreatedAt($date);
        
        // Test fluent interface
        $this->assertSame($this->hotel, $result);
        $this->assertSame($date, $this->hotel->getCreatedAt());
        $this->assertEquals('2024-01-01 12:00:00', $this->hotel->getCreatedAt()->format('Y-m-d H:i:s'));
    }

    public function testGetSetKategorie(): void
    {
        $this->assertNull($this->hotel->getKategorie());
        
        $kategorie = new Kategorie();
        $kategorie->setName('Test Kategorie');
        
        $result = $this->hotel->setKategorie($kategorie);
        
        // Test fluent interface
        $this->assertSame($this->hotel, $result);
        $this->assertSame($kategorie, $this->hotel->getKategorie());
    }

    public function testSetKategorieWithNull(): void
    {
        // First set a kategorie
        $kategorie = new Kategorie();
        $this->hotel->setKategorie($kategorie);
        $this->assertSame($kategorie, $this->hotel->getKategorie());
        
        // Then set to null
        $result = $this->hotel->setKategorie(null);
        
        // Test fluent interface
        $this->assertSame($this->hotel, $result);
        $this->assertNull($this->hotel->getKategorie());
    }

    public function testGetSetRating(): void
    {
        $this->assertNull($this->hotel->getRating());
        
        $result = $this->hotel->setRating('4.5');
        
        // Test fluent interface
        $this->assertSame($this->hotel, $result);
        $this->assertEquals('4.5', $this->hotel->getRating());
        
        // Test with different rating
        $this->hotel->setRating('3.2');
        $this->assertEquals('3.2', $this->hotel->getRating());
    }

    public function testGetSetStars(): void
    {
        $this->assertNull($this->hotel->getStars());
        
        $result = $this->hotel->setStars(5);
        
        // Test fluent interface
        $this->assertSame($this->hotel, $result);
        $this->assertEquals(5, $this->hotel->getStars());
    }

    public function testToString(): void
    {
        // Test with null title
        $this->assertEquals('', $this->hotel->__toString());
        
        // Test with title set
        $this->hotel->setTitle('Test Hotel');
        $this->assertEquals('Test Hotel', $this->hotel->__toString());
        
        // Test with different title
        $this->hotel->setTitle('Another Hotel Name');
        $this->assertEquals('Another Hotel Name', $this->hotel->__toString());
    }

    public function testJsonSerialize(): void
    {
        // Set up hotel with all data
        $kategorie = new Kategorie();
        $kategorie->setName('Test Kategorie');
        
        // Use reflection to set ID for kategorie
        $reflection = new \ReflectionClass($kategorie);
        $idProperty = $reflection->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($kategorie, 1);
        
        // Use reflection to set ID for hotel
        $reflection = new \ReflectionClass($this->hotel);
        $idProperty = $reflection->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($this->hotel, 42);
        
        $date = new \DateTimeImmutable('2024-01-01 12:00:00');
        
        $this->hotel->setTitle('Test Hotel');
        $this->hotel->setLocation('Test Location');
        $this->hotel->setImage('test-image.jpg');
        $this->hotel->setPrice('99.99');
        $this->hotel->setDays(3);
        $this->hotel->setPerson(2);
        $this->hotel->setInfo('Test Info');
        $this->hotel->setDescription('Test Description');
        $this->hotel->setCreatedAt($date);
        $this->hotel->setKategorie($kategorie);
        $this->hotel->setRating('4.5');
        $this->hotel->setStars(4);
        
        $expected = [
            'id' => 42,
            'title' => 'Test Hotel',
            'location' => 'Test Location',
            'image' => 'test-image.jpg',
            'price' => 99.99,
            'days' => 3,
            'person' => 2,
            'info' => 'Test Info',
            'description' => 'Test Description',
            'created_at' => '2024-01-01 12:00:00',
            'kategorie' => [
                'id' => 1,
                'name' => 'Test Kategorie'
            ],
            'rating' => 4.5,
            'stars' => 4
        ];
        
        $this->assertEquals($expected, $this->hotel->jsonSerialize());
    }

    public function testJsonSerializeWithNullValues(): void
    {
        // Test with null values
        $expected = [
            'id' => null,
            'title' => null,
            'location' => null,
            'image' => null,
            'price' => null,
            'days' => null,
            'person' => null,
            'info' => null,
            'description' => null,
            'created_at' => null,
            'kategorie' => null,
            'rating' => null,
            'stars' => null
        ];
        
        $this->assertEquals($expected, $this->hotel->jsonSerialize());
    }

    public function testJsonSerializeWithKategorieOnly(): void
    {
        $kategorie = new Kategorie();
        $kategorie->setName('Only Kategorie');
        
        // Use reflection to set ID for kategorie
        $reflection = new \ReflectionClass($kategorie);
        $idProperty = $reflection->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($kategorie, 5);
        
        $this->hotel->setKategorie($kategorie);
        
        $result = $this->hotel->jsonSerialize();
        
        $this->assertArrayHasKey('kategorie', $result);
        $this->assertEquals([
            'id' => 5,
            'name' => 'Only Kategorie'
        ], $result['kategorie']);
    }

    public function testFluentInterface(): void
    {
        $kategorie = new Kategorie();
        $date = new \DateTimeImmutable();
        
        // Test chaining all setters
        $result = $this->hotel
            ->setTitle('Chain Hotel')
            ->setLocation('Chain Location')
            ->setImage('chain.jpg')
            ->setPrice('150.00')
            ->setDays(5)
            ->setPerson(3)
            ->setInfo('Chain Info')
            ->setDescription('Chain Description')
            ->setCreatedAt($date)
            ->setKategorie($kategorie)
            ->setRating('4.0')
            ->setStars(4);
        
        // All methods should return the same hotel instance
        $this->assertSame($this->hotel, $result);
        
        // Verify all values were set
        $this->assertEquals('Chain Hotel', $this->hotel->getTitle());
        $this->assertEquals('Chain Location', $this->hotel->getLocation());
        $this->assertEquals('chain.jpg', $this->hotel->getImage());
        $this->assertEquals('150.00', $this->hotel->getPrice());
        $this->assertEquals(5, $this->hotel->getDays());
        $this->assertEquals(3, $this->hotel->getPerson());
        $this->assertEquals('Chain Info', $this->hotel->getInfo());
        $this->assertEquals('Chain Description', $this->hotel->getDescription());
        $this->assertSame($date, $this->hotel->getCreatedAt());
        $this->assertSame($kategorie, $this->hotel->getKategorie());
        $this->assertEquals('4.0', $this->hotel->getRating());
        $this->assertEquals(4, $this->hotel->getStars());
    }

    public function testValidDataTypes(): void
    {
        $date = new \DateTimeImmutable('2024-01-01');
        $kategorie = new Kategorie();
        
        // Set all properties
        $this->hotel->setTitle('Type Test');
        $this->hotel->setLocation('Location');
        $this->hotel->setImage('image.jpg');
        $this->hotel->setPrice('99.99');
        $this->hotel->setDays(7);
        $this->hotel->setPerson(2);
        $this->hotel->setInfo('Info');
        $this->hotel->setDescription('Description');
        $this->hotel->setCreatedAt($date);
        $this->hotel->setKategorie($kategorie);
        $this->hotel->setRating('4.5');
        $this->hotel->setStars(5);
        
        // Verify data types
        $this->assertIsString($this->hotel->getTitle());
        $this->assertIsString($this->hotel->getLocation());
        $this->assertIsString($this->hotel->getImage());
        $this->assertIsString($this->hotel->getPrice());
        $this->assertIsInt($this->hotel->getDays());
        $this->assertIsInt($this->hotel->getPerson());
        $this->assertIsString($this->hotel->getInfo());
        $this->assertIsString($this->hotel->getDescription());
        $this->assertInstanceOf(\DateTimeImmutable::class, $this->hotel->getCreatedAt());
        $this->assertInstanceOf(Kategorie::class, $this->hotel->getKategorie());
        $this->assertIsString($this->hotel->getRating());
        $this->assertIsInt($this->hotel->getStars());
    }

    public function testEdgeCases(): void
    {
        // Test with empty strings
        $this->hotel->setTitle('');
        $this->hotel->setLocation('');
        $this->hotel->setImage('');
        $this->hotel->setInfo('');
        $this->hotel->setDescription('');
        
        $this->assertEquals('', $this->hotel->getTitle());
        $this->assertEquals('', $this->hotel->getLocation());
        $this->assertEquals('', $this->hotel->getImage());
        $this->assertEquals('', $this->hotel->getInfo());
        $this->assertEquals('', $this->hotel->getDescription());
        
        // Test with zero values
        $this->hotel->setPrice('0.0');
        $this->hotel->setDays(0);
        $this->hotel->setPerson(0);
        $this->hotel->setRating('0.0');
        $this->hotel->setStars(0);
        
        $this->assertEquals('0.0', $this->hotel->getPrice());
        $this->assertEquals(0, $this->hotel->getDays());
        $this->assertEquals(0, $this->hotel->getPerson());
        $this->assertEquals('0.0', $this->hotel->getRating());
        $this->assertEquals(0, $this->hotel->getStars());
    }
} 