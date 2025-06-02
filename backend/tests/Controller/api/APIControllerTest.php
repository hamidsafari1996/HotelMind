<?php

namespace App\Tests\Controller\api;

use App\Entity\Hotel;
use App\Entity\Kategorie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class APIControllerTest extends WebTestCase
{
    private $client;
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = static::getContainer()->get('doctrine')->getManager();
        
        // Clean database before each test
        $this->cleanDatabase();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->cleanDatabase();
    }

    private function cleanDatabase(): void
    {
        // Remove all hotels first (due to foreign key constraints)
        $hotels = $this->entityManager->getRepository(Hotel::class)->findAll();
        foreach ($hotels as $hotel) {
            $this->entityManager->remove($hotel);
        }
        
        // Remove all categories
        $kategorien = $this->entityManager->getRepository(Kategorie::class)->findAll();
        foreach ($kategorien as $kategorie) {
            $this->entityManager->remove($kategorie);
        }
        
        $this->entityManager->flush();
    }

    private function createKategorie(string $name = 'Test Kategorie'): Kategorie
    {
        $kategorie = new Kategorie();
        $kategorie->setName($name);
        $this->entityManager->persist($kategorie);
        $this->entityManager->flush();
        
        return $kategorie;
    }

    private function createHotel(string $title = 'Test Hotel', ?Kategorie $kategorie = null): Hotel
    {
        if (!$kategorie) {
            $kategorie = $this->createKategorie();
        }

        $hotel = new Hotel();
        $hotel->setTitle($title);
        $hotel->setLocation('Test Location');
        $hotel->setImage('test-image.jpg');
        $hotel->setPrice(99.99);
        $hotel->setDays(3);
        $hotel->setPerson(2);
        $hotel->setInfo('Test Info');
        $hotel->setDescription('Test Description');
        $hotel->setCreatedAt(new \DateTime('2024-01-01 12:00:00'));
        $hotel->setKategorie($kategorie);
        $hotel->setRating(4.5);
        $hotel->setStars(4);
        
        $this->entityManager->persist($hotel);
        $this->entityManager->flush();
        
        return $hotel;
    }

    public function testOptionsHotels(): void
    {
        // Test OPTIONS request for CORS preflight
        $this->client->request('OPTIONS', '/api/hotels');

        $response = $this->client->getResponse();
        
        $this->assertEquals(204, $response->getStatusCode());
        $this->assertEquals('http://localhost:3000', $response->headers->get('Access-Control-Allow-Origin'));
        $this->assertEquals('GET, OPTIONS', $response->headers->get('Access-Control-Allow-Methods'));
        $this->assertEquals('Content-Type', $response->headers->get('Access-Control-Allow-Headers'));
        $this->assertEmpty($response->getContent());
    }

    public function testGetHotelsEmpty(): void
    {
        // Test GET request with no hotels
        $this->client->request('GET', '/api/hotels');

        $response = $this->client->getResponse();
        
        $this->assertResponseIsSuccessful();
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertEquals('http://localhost:3000', $response->headers->get('Access-Control-Allow-Origin'));
        
        $data = json_decode($response->getContent(), true);
        $this->assertIsArray($data);
        $this->assertEmpty($data);
    }

    public function testGetHotelsWithData(): void
    {
        // Create test data
        $kategorie1 = $this->createKategorie('Strandhotel');
        $kategorie2 = $this->createKategorie('Stadthotel');
        
        $hotel1 = $this->createHotel('Beach Resort', $kategorie1);
        $hotel2 = $this->createHotel('City Hotel', $kategorie2);

        // Test GET request
        $this->client->request('GET', '/api/hotels');

        $response = $this->client->getResponse();
        
        $this->assertResponseIsSuccessful();
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertEquals('http://localhost:3000', $response->headers->get('Access-Control-Allow-Origin'));
        
        $data = json_decode($response->getContent(), true);
        
        $this->assertIsArray($data);
        $this->assertCount(2, $data);
        
        // Verify first hotel data structure
        $firstHotel = $data[0];
        $this->assertArrayHasKey('id', $firstHotel);
        $this->assertArrayHasKey('title', $firstHotel);
        $this->assertArrayHasKey('location', $firstHotel);
        $this->assertArrayHasKey('image', $firstHotel);
        $this->assertArrayHasKey('price', $firstHotel);
        $this->assertArrayHasKey('days', $firstHotel);
        $this->assertArrayHasKey('person', $firstHotel);
        $this->assertArrayHasKey('info', $firstHotel);
        $this->assertArrayHasKey('description', $firstHotel);
        $this->assertArrayHasKey('created_at', $firstHotel);
        $this->assertArrayHasKey('kategorie', $firstHotel);
        $this->assertArrayHasKey('rating', $firstHotel);
        $this->assertArrayHasKey('stars', $firstHotel);
        
        // Verify kategorie structure
        $this->assertArrayHasKey('id', $firstHotel['kategorie']);
        $this->assertArrayHasKey('name', $firstHotel['kategorie']);
        
        // Verify data types and values
        $this->assertIsInt($firstHotel['id']);
        $this->assertIsString($firstHotel['title']);
        $this->assertIsFloat($firstHotel['price']);
        $this->assertIsInt($firstHotel['days']);
        $this->assertIsInt($firstHotel['person']);
        $this->assertIsFloat($firstHotel['rating']);
        $this->assertIsInt($firstHotel['stars']);
        
        // Verify specific values
        $this->assertEquals('Test Location', $firstHotel['location']);
        $this->assertEquals(99.99, $firstHotel['price']);
        $this->assertEquals(3, $firstHotel['days']);
        $this->assertEquals(2, $firstHotel['person']);
        $this->assertEquals(4.5, $firstHotel['rating']);
        $this->assertEquals(4, $firstHotel['stars']);
        $this->assertEquals('2024-01-01 12:00:00', $firstHotel['created_at']);
    }

    public function testGetHotelByIdSuccess(): void
    {
        // Create test data
        $kategorie = $this->createKategorie('Luxury Hotel');
        $hotel = $this->createHotel('Luxury Resort', $kategorie);
        $hotelId = $hotel->getId();

        // Test GET request for specific hotel
        $this->client->request('GET', '/api/hotels/' . $hotelId);

        $response = $this->client->getResponse();
        
        $this->assertResponseIsSuccessful();
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertEquals('http://localhost:3000', $response->headers->get('Access-Control-Allow-Origin'));
        
        $data = json_decode($response->getContent(), true);
        
        $this->assertIsArray($data);
        $this->assertEquals($hotelId, $data['id']);
        $this->assertEquals('Luxury Resort', $data['title']);
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
        $this->assertEquals($kategorie->getId(), $data['kategorie']['id']);
        $this->assertEquals('Luxury Hotel', $data['kategorie']['name']);
    }

    public function testGetHotelByIdNotFound(): void
    {
        // Test GET request for non-existent hotel
        $this->client->request('GET', '/api/hotels/999999');

        $response = $this->client->getResponse();
        
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertEquals('http://localhost:3000', $response->headers->get('Access-Control-Allow-Origin'));
        
        $data = json_decode($response->getContent(), true);
        
        $this->assertIsArray($data);
        $this->assertArrayHasKey('error', $data);
        $this->assertEquals('Hotel not found', $data['error']);
    }

    public function testGetHotelByIdInvalidId(): void
    {
        // Test GET request with invalid ID format
        $this->client->request('GET', '/api/hotels/invalid-id');

        $response = $this->client->getResponse();
        
        // Should return 404 due to route parameter requirements
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testJsonResponseFormat(): void
    {
        // Create test hotel
        $kategorie = $this->createKategorie('Test Category');
        $hotel = $this->createHotel('Test Hotel', $kategorie);

        // Test response format
        $this->client->request('GET', '/api/hotels');

        $response = $this->client->getResponse();
        $content = $response->getContent();
        
        // Verify valid JSON
        $this->assertJson($content);
        
        $data = json_decode($content, true);
        $this->assertNotNull($data);
        $this->assertIsArray($data);
        
        // Test that JSON is properly formatted
        $jsonError = json_last_error();
        $this->assertEquals(JSON_ERROR_NONE, $jsonError);
    }

    public function testCorsHeadersConsistency(): void
    {
        // Create test data
        $this->createHotel();

        // Test all endpoints have consistent CORS headers
        $endpoints = [
            ['OPTIONS', '/api/hotels'],
            ['GET', '/api/hotels'],
            ['GET', '/api/hotels/1']
        ];

        foreach ($endpoints as [$method, $url]) {
            $this->client->request($method, $url);
            $response = $this->client->getResponse();
            
            $this->assertEquals(
                'http://localhost:3000',
                $response->headers->get('Access-Control-Allow-Origin'),
                "CORS header missing or incorrect for $method $url"
            );
        }
    }

    public function testMultipleHotelsWithDifferentCategories(): void
    {
        // Create multiple categories and hotels
        $beachCategory = $this->createKategorie('Strandhotel');
        $cityCategory = $this->createKategorie('Stadthotel');
        $mountainCategory = $this->createKategorie('Berghotel');

        $this->createHotel('Beach Paradise', $beachCategory);
        $this->createHotel('City Center Hotel', $cityCategory);
        $this->createHotel('Mountain View Lodge', $mountainCategory);

        // Test GET request
        $this->client->request('GET', '/api/hotels');

        $response = $this->client->getResponse();
        $this->assertResponseIsSuccessful();
        
        $data = json_decode($response->getContent(), true);
        
        $this->assertCount(3, $data);
        
        // Verify all categories are different
        $categories = array_column($data, 'kategorie');
        $categoryNames = array_column($categories, 'name');
        
        $this->assertContains('Strandhotel', $categoryNames);
        $this->assertContains('Stadthotel', $categoryNames);
        $this->assertContains('Berghotel', $categoryNames);
        $this->assertEquals(3, count(array_unique($categoryNames)));
    }

    public function testApiResponsePerformance(): void
    {
        // Create multiple hotels to test performance
        $kategorie = $this->createKategorie();
        
        for ($i = 1; $i <= 10; $i++) {
            $this->createHotel("Hotel $i", $kategorie);
        }

        $startTime = microtime(true);
        
        $this->client->request('GET', '/api/hotels');
        
        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        $response = $this->client->getResponse();
        $this->assertResponseIsSuccessful();
        
        $data = json_decode($response->getContent(), true);
        $this->assertCount(10, $data);
        
        // Performance should be reasonable (less than 1 second for 10 hotels)
        $this->assertLessThan(1.0, $executionTime, 'API response took too long');
    }

    public function testApiContentTypeHeaders(): void
    {
        $this->createHotel();

        // Test all endpoints return proper JSON content type
        $endpoints = [
            ['GET', '/api/hotels'],
            ['GET', '/api/hotels/1']
        ];

        foreach ($endpoints as [$method, $url]) {
            $this->client->request($method, $url);
            $response = $this->client->getResponse();
            
            $contentType = $response->headers->get('Content-Type');
            $this->assertStringContains(
                'application/json',
                $contentType,
                "Content-Type header incorrect for $method $url"
            );
        }
    }

    public function testErrorResponseFormat(): void
    {
        // Test 404 error response format
        $this->client->request('GET', '/api/hotels/999999');

        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        
        $data = json_decode($response->getContent(), true);
        
        // Error response should be valid JSON with error key
        $this->assertIsArray($data);
        $this->assertArrayHasKey('error', $data);
        $this->assertIsString($data['error']);
        $this->assertNotEmpty($data['error']);
    }
}