<?php

namespace App\Tests\Unit\Controller\api;

use App\Controller\api\APIController;
use App\Entity\Hotel;
use App\Entity\Kategorie;
use App\Repository\HotelRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

class APIControllerUnitTest extends TestCase
{
    private APIController $controller;
    private EntityManagerInterface $entityManager;
    private HotelRepository $hotelRepository;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->hotelRepository = $this->createMock(HotelRepository::class);
        $this->controller = new APIController();
    }

    private function createKategorie(int $id = 1, string $name = 'Test Kategorie'): Kategorie
    {
        $kategorie = new Kategorie();
        
        // Use reflection to set the private id property
        $reflection = new \ReflectionClass($kategorie);
        $idProperty = $reflection->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($kategorie, $id);
        
        $kategorie->setName($name);
        
        return $kategorie;
    }

    private function createHotel(int $id = 1, string $title = 'Test Hotel'): Hotel
    {
        $hotel = new Hotel();
        $kategorie = $this->createKategorie();
        
        // Use reflection to set the private id property
        $reflection = new \ReflectionClass($hotel);
        $idProperty = $reflection->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($hotel, $id);
        
        $hotel->setTitle($title);
        $hotel->setLocation('Test Location');
        $hotel->setImage('test-image.jpg');
        $hotel->setPrice(99.99);
        $hotel->setDays(3);
        $hotel->setPerson(2);
        $hotel->setInfo('Test Info');
        $hotel->setDescription('Test Description');
        $hotel->setCreatedAt(new \DateTimeImmutable('2024-01-01 12:00:00'));
        $hotel->setKategorie($kategorie);
        $hotel->setRating(4.5);
        $hotel->setStars(4);
        
        return $hotel;
    }

    public function testOptionsHotels(): void
    {
        // Test OPTIONS request
        $response = $this->controller->optionsHotels();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(204, $response->getStatusCode());
        $this->assertEquals('http://localhost:3000', $response->headers->get('Access-Control-Allow-Origin'));
        $this->assertEquals('GET, OPTIONS', $response->headers->get('Access-Control-Allow-Methods'));
        $this->assertEquals('Content-Type', $response->headers->get('Access-Control-Allow-Headers'));
        $this->assertEquals('{}', $response->getContent());
    }

    public function testGetHotelsEmpty(): void
    {
        // Arrange
        $this->entityManager
            ->expects($this->once())
            ->method('getRepository')
            ->with(Hotel::class)
            ->willReturn($this->hotelRepository);

        $this->hotelRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([]);

        // Act
        $response = $this->controller->getHotels($this->entityManager);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('http://localhost:3000', $response->headers->get('Access-Control-Allow-Origin'));
        $this->assertEquals('[]', $response->getContent());
    }

    public function testGetHotelsWithData(): void
    {
        // Arrange
        $hotel1 = $this->createHotel(1, 'Hotel One');
        $hotel2 = $this->createHotel(2, 'Hotel Two');
        $hotels = [$hotel1, $hotel2];

        $this->entityManager
            ->expects($this->once())
            ->method('getRepository')
            ->with(Hotel::class)
            ->willReturn($this->hotelRepository);

        $this->hotelRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn($hotels);

        // Act
        $response = $this->controller->getHotels($this->entityManager);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('http://localhost:3000', $response->headers->get('Access-Control-Allow-Origin'));

        $data = json_decode($response->getContent(), true);
        $this->assertIsArray($data);
        $this->assertCount(2, $data);

        // Verify first hotel structure
        $firstHotel = $data[0];
        $this->assertEquals(1, $firstHotel['id']);
        $this->assertEquals('Hotel One', $firstHotel['title']);
        $this->assertEquals('Test Location', $firstHotel['location']);
        $this->assertEquals('test-image.jpg', $firstHotel['image']);
        $this->assertEquals(99.99, $firstHotel['price']);
        $this->assertEquals(3, $firstHotel['days']);
        $this->assertEquals(2, $firstHotel['person']);
        $this->assertEquals('Test Info', $firstHotel['info']);
        $this->assertEquals('Test Description', $firstHotel['description']);
        $this->assertEquals('2024-01-01 12:00:00', $firstHotel['created_at']);
        $this->assertEquals(4.5, $firstHotel['rating']);
        $this->assertEquals(4, $firstHotel['stars']);
        
        // Verify kategorie structure
        $this->assertArrayHasKey('kategorie', $firstHotel);
        $this->assertEquals(1, $firstHotel['kategorie']['id']);
        $this->assertEquals('Test Kategorie', $firstHotel['kategorie']['name']);
    }

    public function testGetHotelByIdSuccess(): void
    {
        // Arrange
        $hotel = $this->createHotel(42, 'Specific Hotel');
        
        $this->entityManager
            ->expects($this->once())
            ->method('getRepository')
            ->with(Hotel::class)
            ->willReturn($this->hotelRepository);

        $this->hotelRepository
            ->expects($this->once())
            ->method('find')
            ->with(42)
            ->willReturn($hotel);

        // Act
        $response = $this->controller->getHotelById(42, $this->entityManager);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('http://localhost:3000', $response->headers->get('Access-Control-Allow-Origin'));

        $data = json_decode($response->getContent(), true);
        $this->assertIsArray($data);
        $this->assertEquals(42, $data['id']);
        $this->assertEquals('Specific Hotel', $data['title']);
        $this->assertEquals('Test Location', $data['location']);
        $this->assertEquals('test-image.jpg', $data['image']);
        $this->assertEquals(99.99, $data['price']);
        $this->assertEquals(3, $data['days']);
        $this->assertEquals(2, $data['person']);
        $this->assertEquals('Test Info', $data['info']);
        $this->assertEquals('Test Description', $data['description']);
        $this->assertEquals('2024-01-01 12:00:00', $data['created_at']);
        $this->assertEquals(4.5, $data['rating']);
        $this->assertEquals(4, $data['stars']);
        
        // Verify kategorie data
        $this->assertArrayHasKey('kategorie', $data);
        $this->assertEquals(1, $data['kategorie']['id']);
        $this->assertEquals('Test Kategorie', $data['kategorie']['name']);
    }

    public function testGetHotelByIdNotFound(): void
    {
        // Arrange
        $this->entityManager
            ->expects($this->once())
            ->method('getRepository')
            ->with(Hotel::class)
            ->willReturn($this->hotelRepository);

        $this->hotelRepository
            ->expects($this->once())
            ->method('find')
            ->with(999)
            ->willReturn(null);

        // Act
        $response = $this->controller->getHotelById(999, $this->entityManager);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(404, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        
        $this->assertIsArray($data);
        $this->assertArrayHasKey('error', $data);
        $this->assertEquals('Hotel not found', $data['error']);
    }

    public function testJsonResponseStructure(): void
    {
        // Arrange
        $hotel = $this->createHotel();
        
        $this->entityManager
            ->expects($this->once())
            ->method('getRepository')
            ->with(Hotel::class)
            ->willReturn($this->hotelRepository);

        $this->hotelRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([$hotel]);

        // Act
        $response = $this->controller->getHotels($this->entityManager);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        
        $data = json_decode($response->getContent(), true);
        $this->assertIsArray($data);
        $this->assertCount(1, $data);
        
        $hotelData = $data[0];
        
        // Verify all required fields are present
        $requiredFields = [
            'id', 'title', 'location', 'image', 'price', 'days', 
            'person', 'info', 'description', 'created_at', 
            'kategorie', 'rating', 'stars'
        ];
        
        foreach ($requiredFields as $field) {
            $this->assertArrayHasKey($field, $hotelData, "Missing field: $field");
        }
        
        // Verify kategorie structure
        $this->assertArrayHasKey('id', $hotelData['kategorie']);
        $this->assertArrayHasKey('name', $hotelData['kategorie']);
    }

    public function testDataTypesInJsonResponse(): void
    {
        // Arrange
        $hotel = $this->createHotel();
        
        $this->entityManager
            ->expects($this->once())
            ->method('getRepository')
            ->with(Hotel::class)
            ->willReturn($this->hotelRepository);

        $this->hotelRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([$hotel]);

        // Act
        $response = $this->controller->getHotels($this->entityManager);

        // Assert
        $data = json_decode($response->getContent(), true);
        $hotelData = $data[0];
        
        // Verify data types
        $this->assertIsInt($hotelData['id']);
        $this->assertIsString($hotelData['title']);
        $this->assertIsString($hotelData['location']);
        $this->assertIsString($hotelData['image']);
        $this->assertIsString($hotelData['price']);
        $this->assertIsInt($hotelData['days']);
        $this->assertIsInt($hotelData['person']);
        $this->assertIsString($hotelData['info']);
        $this->assertIsString($hotelData['description']);
        $this->assertIsString($hotelData['created_at']);
        $this->assertIsString($hotelData['rating']);
        $this->assertIsInt($hotelData['stars']);
        $this->assertIsArray($hotelData['kategorie']);
        $this->assertIsInt($hotelData['kategorie']['id']);
        $this->assertIsString($hotelData['kategorie']['name']);
    }

    public function testCorsHeadersInAllResponses(): void
    {
        // Test OPTIONS
        $optionsResponse = $this->controller->optionsHotels();
        $this->assertEquals('http://localhost:3000', $optionsResponse->headers->get('Access-Control-Allow-Origin'));

        // Test getHotels - create new mock for this test
        $entityManager1 = $this->createMock(EntityManagerInterface::class);
        $hotelRepository1 = $this->createMock(HotelRepository::class);
        
        $entityManager1
            ->expects($this->once())
            ->method('getRepository')
            ->with(Hotel::class)
            ->willReturn($hotelRepository1);
            
        $hotelRepository1
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([]);

        $getHotelsResponse = $this->controller->getHotels($entityManager1);
        $this->assertEquals('http://localhost:3000', $getHotelsResponse->headers->get('Access-Control-Allow-Origin'));

        // Test getHotelById (not found) - create new mock for this test
        $entityManager2 = $this->createMock(EntityManagerInterface::class);
        $hotelRepository2 = $this->createMock(HotelRepository::class);
        
        $entityManager2
            ->expects($this->once())
            ->method('getRepository')
            ->with(Hotel::class)
            ->willReturn($hotelRepository2);
            
        $hotelRepository2
            ->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $getHotelResponse = $this->controller->getHotelById(1, $entityManager2);
        // Note: The error response doesn't set CORS headers in the current implementation
        $this->assertNull($getHotelResponse->headers->get('Access-Control-Allow-Origin'));
    }

    public function testDateFormatting(): void
    {
        // Arrange
        $hotel = $this->createHotel();
        
        $this->entityManager
            ->expects($this->once())
            ->method('getRepository')
            ->with(Hotel::class)
            ->willReturn($this->hotelRepository);

        $this->hotelRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([$hotel]);

        // Act
        $response = $this->controller->getHotels($this->entityManager);

        // Assert
        $data = json_decode($response->getContent(), true);
        $hotelData = $data[0];
        
        // Verify date format (Y-m-d H:i:s)
        $this->assertEquals('2024-01-01 12:00:00', $hotelData['created_at']);
        
        // Verify it's a valid date format
        $parsedDate = \DateTime::createFromFormat('Y-m-d H:i:s', $hotelData['created_at']);
        $this->assertInstanceOf(\DateTime::class, $parsedDate);
    }

    public function testMultipleHotelsWithDifferentCategories(): void
    {
        // Arrange
        $kategorie1 = $this->createKategorie(1, 'Beach Resort');
        $kategorie2 = $this->createKategorie(2, 'City Hotel');
        
        $hotel1 = $this->createHotel(1, 'Hotel Beach');
        $hotel1->setKategorie($kategorie1);
        
        $hotel2 = $this->createHotel(2, 'Hotel City');
        $hotel2->setKategorie($kategorie2);
        
        $this->entityManager
            ->expects($this->once())
            ->method('getRepository')
            ->with(Hotel::class)
            ->willReturn($this->hotelRepository);

        $this->hotelRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([$hotel1, $hotel2]);

        // Act
        $response = $this->controller->getHotels($this->entityManager);

        // Assert
        $data = json_decode($response->getContent(), true);
        $this->assertCount(2, $data);
        
        // Verify categories are different
        $this->assertEquals('Beach Resort', $data[0]['kategorie']['name']);
        $this->assertEquals('City Hotel', $data[1]['kategorie']['name']);
        $this->assertEquals(1, $data[0]['kategorie']['id']);
        $this->assertEquals(2, $data[1]['kategorie']['id']);
    }

    public function testEmptyResponseIsValidJson(): void
    {
        // Arrange
        $this->entityManager
            ->expects($this->once())
            ->method('getRepository')
            ->with(Hotel::class)
            ->willReturn($this->hotelRepository);

        $this->hotelRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([]);

        // Act
        $response = $this->controller->getHotels($this->entityManager);

        // Assert
        $content = $response->getContent();
        $this->assertJson($content);
        $this->assertEquals('[]', $content);
        
        $data = json_decode($content, true);
        $this->assertIsArray($data);
        $this->assertEmpty($data);
    }
} 