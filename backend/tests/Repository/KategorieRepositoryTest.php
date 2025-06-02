<?php

namespace App\Tests\Repository;

use App\Entity\Kategorie;
use App\Repository\KategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class KategorieRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private KategorieRepository $kategorieRepository;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = static::getContainer()->get('doctrine')->getManager();
        $this->kategorieRepository = $this->entityManager->getRepository(Kategorie::class);
        
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
        $kategorien = $this->kategorieRepository->findAll();
        foreach ($kategorien as $kategorie) {
            $this->entityManager->remove($kategorie);
        }
        $this->entityManager->flush();
    }

    private function createKategorie(string $name): Kategorie
    {
        $kategorie = new Kategorie();
        $kategorie->setName($name);
        $this->entityManager->persist($kategorie);
        $this->entityManager->flush();
        
        return $kategorie;
    }

    public function testFindAll(): void
    {
        // Create test data
        $this->createKategorie('Kategorie 1');
        $this->createKategorie('Kategorie 2');
        $this->createKategorie('Kategorie 3');

        // Test findAll
        $kategorien = $this->kategorieRepository->findAll();

        $this->assertCount(3, $kategorien);
        $this->assertContainsOnlyInstancesOf(Kategorie::class, $kategorien);
        
        $names = array_map(fn($k) => $k->getName(), $kategorien);
        $this->assertContains('Kategorie 1', $names);
        $this->assertContains('Kategorie 2', $names);
        $this->assertContains('Kategorie 3', $names);
    }

    public function testFindAllEmpty(): void
    {
        // Test findAll with empty database
        $kategorien = $this->kategorieRepository->findAll();

        $this->assertCount(0, $kategorien);
        $this->assertIsArray($kategorien);
    }

    public function testFindById(): void
    {
        // Create test data
        $kategorie = $this->createKategorie('Test Kategorie');
        $id = $kategorie->getId();

        // Test find by ID
        $foundKategorie = $this->kategorieRepository->find($id);

        $this->assertNotNull($foundKategorie);
        $this->assertInstanceOf(Kategorie::class, $foundKategorie);
        $this->assertEquals($id, $foundKategorie->getId());
        $this->assertEquals('Test Kategorie', $foundKategorie->getName());
    }

    public function testFindByIdNotFound(): void
    {
        // Test find with non-existent ID
        $foundKategorie = $this->kategorieRepository->find(999999);

        $this->assertNull($foundKategorie);
    }

    public function testFindOneBy(): void
    {
        // Create test data
        $this->createKategorie('Unique Kategorie');
        $this->createKategorie('Another Kategorie');

        // Test findOneBy
        $foundKategorie = $this->kategorieRepository->findOneBy(['name' => 'Unique Kategorie']);

        $this->assertNotNull($foundKategorie);
        $this->assertInstanceOf(Kategorie::class, $foundKategorie);
        $this->assertEquals('Unique Kategorie', $foundKategorie->getName());
    }

    public function testFindOneByNotFound(): void
    {
        // Test findOneBy with non-existent criteria
        $foundKategorie = $this->kategorieRepository->findOneBy(['name' => 'Non Existent']);

        $this->assertNull($foundKategorie);
    }

    public function testFindBy(): void
    {
        // Create test data with same starting name
        $this->createKategorie('Test Category 1');
        $this->createKategorie('Test Category 2');
        $this->createKategorie('Different Category');

        // Test findBy - this would require custom repository methods
        // For now, test basic functionality
        $allKategorien = $this->kategorieRepository->findBy([]);
        $this->assertCount(3, $allKategorien);
    }

    public function testCount(): void
    {
        // Test count with empty database
        $count = $this->kategorieRepository->count([]);
        $this->assertEquals(0, $count);

        // Add some categories
        $this->createKategorie('Category 1');
        $this->createKategorie('Category 2');

        // Test count with data
        $count = $this->kategorieRepository->count([]);
        $this->assertEquals(2, $count);
    }

    public function testPersistAndFlush(): void
    {
        // Create new kategorie
        $kategorie = new Kategorie();
        $kategorie->setName('New Persistent Kategorie');

        // Persist and flush
        $this->entityManager->persist($kategorie);
        $this->entityManager->flush();

        // Verify it was saved
        $this->assertNotNull($kategorie->getId());
        
        // Verify it can be found
        $foundKategorie = $this->kategorieRepository->find($kategorie->getId());
        $this->assertNotNull($foundKategorie);
        $this->assertEquals('New Persistent Kategorie', $foundKategorie->getName());
    }

    public function testRemove(): void
    {
        // Create and save kategorie
        $kategorie = $this->createKategorie('To Be Removed');
        $id = $kategorie->getId();

        // Verify it exists
        $foundKategorie = $this->kategorieRepository->find($id);
        $this->assertNotNull($foundKategorie);

        // Remove it
        $this->entityManager->remove($kategorie);
        $this->entityManager->flush();

        // Verify it was removed
        $foundKategorie = $this->kategorieRepository->find($id);
        $this->assertNull($foundKategorie);
    }

    public function testUpdate(): void
    {
        // Create kategorie
        $kategorie = $this->createKategorie('Original Name');
        $id = $kategorie->getId();

        // Update name
        $kategorie->setName('Updated Name');
        $this->entityManager->flush();

        // Clear entity manager to ensure fresh fetch
        $this->entityManager->clear();

        // Verify update
        $updatedKategorie = $this->kategorieRepository->find($id);
        $this->assertNotNull($updatedKategorie);
        $this->assertEquals('Updated Name', $updatedKategorie->getName());
    }
} 