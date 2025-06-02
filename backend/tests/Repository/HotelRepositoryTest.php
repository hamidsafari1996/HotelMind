<?php

namespace App\Tests\Repository;

use App\Entity\Hotel;
use App\Entity\Kategorie;
use App\Repository\HotelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class HotelRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private HotelRepository $hotelRepository;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = static::getContainer()->get('doctrine')->getManager();
        $this->hotelRepository = $this->entityManager->getRepository(Hotel::class);
        
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
        $hotels = $this->hotelRepository->findAll();
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
        $hotel->setCreatedAt(new \DateTimeImmutable('2024-01-01 12:00:00'));
        $hotel->setKategorie($kategorie);
        $hotel->setRating(4.5);
        $hotel->setStars(4);
        
        $this->entityManager->persist($hotel);
        $this->entityManager->flush();
        
        return $hotel;
    }

    public function testFindAll(): void
    {
        // Create test data
        $this->createHotel('Hotel 1');
        $this->createHotel('Hotel 2');
        $this->createHotel('Hotel 3');

        // Test findAll
        $hotels = $this->hotelRepository->findAll();

        $this->assertCount(3, $hotels);
        $this->assertContainsOnlyInstancesOf(Hotel::class, $hotels);
        
        $titles = array_map(fn($h) => $h->getTitle(), $hotels);
        $this->assertContains('Hotel 1', $titles);
        $this->assertContains('Hotel 2', $titles);
        $this->assertContains('Hotel 3', $titles);
    }

    public function testFindAllEmpty(): void
    {
        // Test findAll with empty database
        $hotels = $this->hotelRepository->findAll();

        $this->assertCount(0, $hotels);
        $this->assertIsArray($hotels);
    }

    public function testFindById(): void
    {
        // Create test data
        $hotel = $this->createHotel('Test Hotel');
        $id = $hotel->getId();

        // Test find by ID
        $foundHotel = $this->hotelRepository->find($id);

        $this->assertNotNull($foundHotel);
        $this->assertInstanceOf(Hotel::class, $foundHotel);
        $this->assertEquals($id, $foundHotel->getId());
        $this->assertEquals('Test Hotel', $foundHotel->getTitle());
    }

    public function testFindByIdNotFound(): void
    {
        // Test find with non-existent ID
        $foundHotel = $this->hotelRepository->find(999999);

        $this->assertNull($foundHotel);
    }

    public function testFindOneBy(): void
    {
        // Create test data
        $this->createHotel('Unique Hotel');
        $this->createHotel('Another Hotel');

        // Test findOneBy
        $foundHotel = $this->hotelRepository->findOneBy(['title' => 'Unique Hotel']);

        $this->assertNotNull($foundHotel);
        $this->assertInstanceOf(Hotel::class, $foundHotel);
        $this->assertEquals('Unique Hotel', $foundHotel->getTitle());
    }

    public function testFindOneByNotFound(): void
    {
        // Test findOneBy with non-existent criteria
        $foundHotel = $this->hotelRepository->findOneBy(['title' => 'Non Existent']);

        $this->assertNull($foundHotel);
    }

    public function testFindByKategorie(): void
    {
        // Create categories
        $beachCategory = $this->createKategorie('Beach Resort');
        $cityCategory = $this->createKategorie('City Hotel');

        // Create hotels
        $this->createHotel('Beach Hotel 1', $beachCategory);
        $this->createHotel('Beach Hotel 2', $beachCategory);
        $this->createHotel('City Hotel 1', $cityCategory);

        // Test findBy kategorie
        $beachHotels = $this->hotelRepository->findBy(['kategorie' => $beachCategory]);
        $cityHotels = $this->hotelRepository->findBy(['kategorie' => $cityCategory]);

        $this->assertCount(2, $beachHotels);
        $this->assertCount(1, $cityHotels);
        
        foreach ($beachHotels as $hotel) {
            $this->assertEquals($beachCategory->getId(), $hotel->getKategorie()->getId());
        }
    }

    public function testFindByPrice(): void
    {
        // Create hotels with different prices
        $hotel1 = $this->createHotel('Cheap Hotel');
        $hotel1->setPrice(50.00);
        
        $hotel2 = $this->createHotel('Expensive Hotel');
        $hotel2->setPrice(200.00);
        
        $hotel3 = $this->createHotel('Medium Hotel');
        $hotel3->setPrice(100.00);
        
        $this->entityManager->flush();

        // Test findBy price
        $cheapHotels = $this->hotelRepository->findBy(['price' => 50.00]);
        $expensiveHotels = $this->hotelRepository->findBy(['price' => 200.00]);

        $this->assertCount(1, $cheapHotels);
        $this->assertCount(1, $expensiveHotels);
        $this->assertEquals('Cheap Hotel', $cheapHotels[0]->getTitle());
        $this->assertEquals('Expensive Hotel', $expensiveHotels[0]->getTitle());
    }

    public function testFindByLocation(): void
    {
        // Create hotels with different locations
        $hotel1 = $this->createHotel('Hotel Paris');
        $hotel1->setLocation('Paris');
        
        $hotel2 = $this->createHotel('Hotel London');
        $hotel2->setLocation('London');
        
        $hotel3 = $this->createHotel('Another Paris Hotel');
        $hotel3->setLocation('Paris');
        
        $this->entityManager->flush();

        // Test findBy location
        $parisHotels = $this->hotelRepository->findBy(['location' => 'Paris']);
        $londonHotels = $this->hotelRepository->findBy(['location' => 'London']);

        $this->assertCount(2, $parisHotels);
        $this->assertCount(1, $londonHotels);
    }

    public function testCount(): void
    {
        // Test count with empty database
        $count = $this->hotelRepository->count([]);
        $this->assertEquals(0, $count);

        // Add some hotels
        $this->createHotel('Hotel 1');
        $this->createHotel('Hotel 2');

        // Test count with data
        $count = $this->hotelRepository->count([]);
        $this->assertEquals(2, $count);
    }

    public function testCountByKategorie(): void
    {
        // Create categories
        $beachCategory = $this->createKategorie('Beach Resort');
        $cityCategory = $this->createKategorie('City Hotel');

        // Create hotels
        $this->createHotel('Beach Hotel 1', $beachCategory);
        $this->createHotel('Beach Hotel 2', $beachCategory);
        $this->createHotel('City Hotel 1', $cityCategory);

        // Test count by kategorie
        $beachCount = $this->hotelRepository->count(['kategorie' => $beachCategory]);
        $cityCount = $this->hotelRepository->count(['kategorie' => $cityCategory]);

        $this->assertEquals(2, $beachCount);
        $this->assertEquals(1, $cityCount);
    }

    public function testPersistAndFlush(): void
    {
        // Create new hotel
        $kategorie = $this->createKategorie();
        $hotel = new Hotel();
        $hotel->setTitle('New Persistent Hotel');
        $hotel->setLocation('New Location');
        $hotel->setImage('new-image.jpg');
        $hotel->setPrice(149.99);
        $hotel->setDays(5);
        $hotel->setPerson(3);
        $hotel->setInfo('New Info');
        $hotel->setDescription('New Description');
        $hotel->setCreatedAt(new \DateTimeImmutable());
        $hotel->setKategorie($kategorie);
        $hotel->setRating(4.2);
        $hotel->setStars(4);

        // Persist and flush
        $this->entityManager->persist($hotel);
        $this->entityManager->flush();

        // Verify it was saved
        $this->assertNotNull($hotel->getId());
        
        // Verify it can be found
        $foundHotel = $this->hotelRepository->find($hotel->getId());
        $this->assertNotNull($foundHotel);
        $this->assertEquals('New Persistent Hotel', $foundHotel->getTitle());
    }

    public function testRemove(): void
    {
        // Create and save hotel
        $hotel = $this->createHotel('To Be Removed');
        $id = $hotel->getId();

        // Verify it exists
        $foundHotel = $this->hotelRepository->find($id);
        $this->assertNotNull($foundHotel);

        // Remove it
        $this->entityManager->remove($hotel);
        $this->entityManager->flush();

        // Verify it was removed
        $foundHotel = $this->hotelRepository->find($id);
        $this->assertNull($foundHotel);
    }

    public function testUpdate(): void
    {
        // Create hotel
        $hotel = $this->createHotel('Original Title');
        $id = $hotel->getId();

        // Update title
        $hotel->setTitle('Updated Title');
        $hotel->setPrice(199.99);
        $this->entityManager->flush();

        // Clear entity manager to ensure fresh fetch
        $this->entityManager->clear();

        // Verify update
        $updatedHotel = $this->hotelRepository->find($id);
        $this->assertNotNull($updatedHotel);
        $this->assertEquals('Updated Title', $updatedHotel->getTitle());
        $this->assertEquals(199.99, $updatedHotel->getPrice());
    }

    public function testFindByMultipleCriteria(): void
    {
        // Create category
        $kategorie = $this->createKategorie('Luxury Resort');

        // Create hotels
        $hotel1 = $this->createHotel('Luxury Hotel Paris', $kategorie);
        $hotel1->setLocation('Paris');
        $hotel1->setStars(5);
        
        $hotel2 = $this->createHotel('Luxury Hotel London', $kategorie);
        $hotel2->setLocation('London');
        $hotel2->setStars(5);
        
        $hotel3 = $this->createHotel('Budget Hotel Paris');
        $hotel3->setLocation('Paris');
        $hotel3->setStars(3);
        
        $this->entityManager->flush();

        // Test findBy multiple criteria
        $luxuryParis = $this->hotelRepository->findBy([
            'kategorie' => $kategorie,
            'location' => 'Paris'
        ]);

        $fiveStarHotels = $this->hotelRepository->findBy(['stars' => 5]);

        $this->assertCount(1, $luxuryParis);
        $this->assertEquals('Luxury Hotel Paris', $luxuryParis[0]->getTitle());
        
        $this->assertCount(2, $fiveStarHotels);
    }

    public function testFindWithOrderBy(): void
    {
        // Create hotels with different prices
        $hotel1 = $this->createHotel('Expensive Hotel');
        $hotel1->setPrice(300.00);
        
        $hotel2 = $this->createHotel('Cheap Hotel');
        $hotel2->setPrice(50.00);
        
        $hotel3 = $this->createHotel('Medium Hotel');
        $hotel3->setPrice(150.00);
        
        $this->entityManager->flush();

        // Test findBy with order
        $hotelsAsc = $this->hotelRepository->findBy([], ['price' => 'ASC']);
        $hotelsDesc = $this->hotelRepository->findBy([], ['price' => 'DESC']);

        $this->assertCount(3, $hotelsAsc);
        $this->assertCount(3, $hotelsDesc);
        
        // Check ascending order
        $this->assertEquals('Cheap Hotel', $hotelsAsc[0]->getTitle());
        $this->assertEquals('Medium Hotel', $hotelsAsc[1]->getTitle());
        $this->assertEquals('Expensive Hotel', $hotelsAsc[2]->getTitle());
        
        // Check descending order
        $this->assertEquals('Expensive Hotel', $hotelsDesc[0]->getTitle());
        $this->assertEquals('Medium Hotel', $hotelsDesc[1]->getTitle());
        $this->assertEquals('Cheap Hotel', $hotelsDesc[2]->getTitle());
    }

    public function testFindWithLimit(): void
    {
        // Create multiple hotels
        for ($i = 1; $i <= 5; $i++) {
            $this->createHotel("Hotel $i");
        }

        // Test findBy with limit
        $limitedHotels = $this->hotelRepository->findBy([], null, 3);

        $this->assertCount(3, $limitedHotels);
        $this->assertContainsOnlyInstancesOf(Hotel::class, $limitedHotels);
    }

    public function testFindWithOffset(): void
    {
        // Create multiple hotels
        for ($i = 1; $i <= 5; $i++) {
            $this->createHotel("Hotel $i");
        }

        // Test findBy with offset
        $offsetHotels = $this->hotelRepository->findBy([], null, 2, 2);

        $this->assertCount(2, $offsetHotels);
        $this->assertContainsOnlyInstancesOf(Hotel::class, $offsetHotels);
    }

    public function testRepositoryClassName(): void
    {
        $this->assertInstanceOf(HotelRepository::class, $this->hotelRepository);
    }

    public function testEntityManagerIntegration(): void
    {
        // Test that repository is properly connected to entity manager
        $hotel = $this->createHotel('Integration Test Hotel');
        
        // Find using repository
        $foundHotel = $this->hotelRepository->find($hotel->getId());
        
        // Verify they are the same object (managed by same entity manager)
        $this->assertSame($hotel, $foundHotel);
    }
} 