<?php

namespace App\Tests\Controller;

use App\Entity\Kategorie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class KategorieControllerTest extends WebTestCase
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

    public function testIndex(): void
    {
        // Create test data
        $kategorie1 = $this->createKategorie('Kategorie 1');
        $kategorie2 = $this->createKategorie('Kategorie 2');

        // Test index page
        $crawler = $this->client->request('GET', '/kategorie');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Kategorie index');
        $this->assertSelectorTextContains('body', 'Kategorie 1');
        $this->assertSelectorTextContains('body', 'Kategorie 2');
    }

    public function testIndexEmpty(): void
    {
        // Test index page with no categories
        $crawler = $this->client->request('GET', '/kategorie');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Kategorie index');
    }

    public function testNew(): void
    {
        // Test GET request for new form
        $crawler = $this->client->request('GET', '/kategorie/new');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form[name="kategorie"]');
        $this->assertSelectorExists('input[name="kategorie[name]"]');
    }

    public function testCreateKategorie(): void
    {
        // Test POST request to create new category
        $crawler = $this->client->request('GET', '/kategorie/new');
        
        $form = $crawler->selectButton('Save')->form([
            'kategorie[name]' => 'Neue Test Kategorie'
        ]);

        $this->client->submit($form);

        $this->assertResponseRedirects('/kategorie');
        
        // Follow redirect and check if category was created
        $this->client->followRedirect();
        $this->assertSelectorTextContains('body', 'Neue Test Kategorie');

        // Verify in database
        $kategorie = $this->entityManager->getRepository(Kategorie::class)
            ->findOneBy(['name' => 'Neue Test Kategorie']);
        $this->assertNotNull($kategorie);
        $this->assertEquals('Neue Test Kategorie', $kategorie->getName());
    }

    public function testCreateKategorieWithEmptyName(): void
    {
        // Test POST request with empty name (should fail validation)
        $crawler = $this->client->request('GET', '/kategorie/new');
        
        $form = $crawler->selectButton('Save')->form([
            'kategorie[name]' => ''
        ]);

        $this->client->submit($form);

        // Should not redirect (form has errors)
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.invalid-feedback, .form-error');
    }

    public function testShow(): void
    {
        // Create test category
        $kategorie = $this->createKategorie('Show Test Kategorie');

        // Test show page
        $crawler = $this->client->request('GET', '/kategorie/' . $kategorie->getId());

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('body', 'Show Test Kategorie');
        $this->assertSelectorTextContains('body', (string)$kategorie->getId());
    }

    public function testShowNotFound(): void
    {
        // Test with non-existent ID
        $this->client->request('GET', '/kategorie/999999');

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testEdit(): void
    {
        // Create test category
        $kategorie = $this->createKategorie('Original Name');

        // Test GET request for edit form
        $crawler = $this->client->request('GET', '/kategorie/' . $kategorie->getId() . '/edit');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form[name="kategorie"]');
        $this->assertInputValueSame('kategorie[name]', 'Original Name');
    }

    public function testUpdateKategorie(): void
    {
        // Create test category
        $kategorie = $this->createKategorie('Original Name');
        $originalId = $kategorie->getId();

        // Test POST request to update category
        $crawler = $this->client->request('GET', '/kategorie/' . $originalId . '/edit');
        
        $form = $crawler->selectButton('Update')->form([
            'kategorie[name]' => 'Updated Name'
        ]);

        $this->client->submit($form);

        $this->assertResponseRedirects('/kategorie');
        
        // Follow redirect and check if category was updated
        $this->client->followRedirect();
        $this->assertSelectorTextContains('body', 'Updated Name');

        // Verify in database
        $this->entityManager->refresh($kategorie);
        $this->assertEquals('Updated Name', $kategorie->getName());
    }

    public function testEditNotFound(): void
    {
        // Test with non-existent ID
        $this->client->request('GET', '/kategorie/999999/edit');

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testDelete(): void
    {
        // Create test category
        $kategorie = $this->createKategorie('To Delete');
        $kategorieId = $kategorie->getId();

        // Get CSRF token
        $crawler = $this->client->request('GET', '/kategorie');
        $token = $this->client->getContainer()->get('security.csrf.token_manager')
            ->getToken('delete' . $kategorieId)->getValue();

        // Test POST request to delete category
        $this->client->request('POST', '/kategorie/' . $kategorieId, [
            '_token' => $token
        ]);

        $this->assertResponseRedirects('/kategorie');

        // Verify category was deleted from database
        $deletedKategorie = $this->entityManager->getRepository(Kategorie::class)
            ->find($kategorieId);
        $this->assertNull($deletedKategorie);
    }

    public function testDeleteWithInvalidCsrfToken(): void
    {
        // Create test category
        $kategorie = $this->createKategorie('To Delete');
        $kategorieId = $kategorie->getId();

        // Test POST request with invalid CSRF token
        $this->client->request('POST', '/kategorie/' . $kategorieId, [
            '_token' => 'invalid_token'
        ]);

        $this->assertResponseRedirects('/kategorie');

        // Verify category was NOT deleted from database
        $notDeletedKategorie = $this->entityManager->getRepository(Kategorie::class)
            ->find($kategorieId);
        $this->assertNotNull($notDeletedKategorie);
    }

    public function testDeleteNotFound(): void
    {
        // Test with non-existent ID
        $this->client->request('POST', '/kategorie/999999', [
            '_token' => 'some_token'
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testCompleteWorkflow(): void
    {
        // Test complete workflow: create -> view -> edit -> delete
        
        // 1. Create
        $crawler = $this->client->request('GET', '/kategorie/new');
        $form = $crawler->selectButton('Save')->form([
            'kategorie[name]' => 'Workflow Test'
        ]);
        $this->client->submit($form);
        $this->assertResponseRedirects('/kategorie');

        // Get the created category
        $kategorie = $this->entityManager->getRepository(Kategorie::class)
            ->findOneBy(['name' => 'Workflow Test']);
        $this->assertNotNull($kategorie);
        $kategorieId = $kategorie->getId();

        // 2. View
        $this->client->request('GET', '/kategorie/' . $kategorieId);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('body', 'Workflow Test');

        // 3. Edit
        $crawler = $this->client->request('GET', '/kategorie/' . $kategorieId . '/edit');
        $form = $crawler->selectButton('Update')->form([
            'kategorie[name]' => 'Workflow Test Updated'
        ]);
        $this->client->submit($form);
        $this->assertResponseRedirects('/kategorie');

        // 4. Verify edit
        $this->entityManager->refresh($kategorie);
        $this->assertEquals('Workflow Test Updated', $kategorie->getName());

        // 5. Delete
        $token = $this->client->getContainer()->get('security.csrf.token_manager')
            ->getToken('delete' . $kategorieId)->getValue();
        $this->client->request('POST', '/kategorie/' . $kategorieId, [
            '_token' => $token
        ]);
        $this->assertResponseRedirects('/kategorie');

        // 6. Verify deletion
        $deletedKategorie = $this->entityManager->getRepository(Kategorie::class)
            ->find($kategorieId);
        $this->assertNull($deletedKategorie);
    }
} 