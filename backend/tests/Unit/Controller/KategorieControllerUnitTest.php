<?php

namespace App\Tests\Unit\Controller;

use App\Controller\KategorieController;
use App\Entity\Kategorie;
use App\Form\KategorieType;
use App\Repository\KategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Twig\Environment;

class KategorieControllerUnitTest extends TestCase
{
    private KategorieController $controller;
    private KategorieRepository $kategorieRepository;
    private EntityManagerInterface $entityManager;
    private FormFactoryInterface $formFactory;
    private Environment $twig;
    private RouterInterface $router;
    private CsrfTokenManagerInterface $csrfTokenManager;

    protected function setUp(): void
    {
        $this->kategorieRepository = $this->createMock(KategorieRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->formFactory = $this->createMock(FormFactoryInterface::class);
        $this->twig = $this->createMock(Environment::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->csrfTokenManager = $this->createMock(CsrfTokenManagerInterface::class);

        $this->controller = new KategorieController();
        
        // Use reflection to inject dependencies since this is a unit test
        $this->injectPrivateProperty('twig', $this->twig);
        $this->injectPrivateProperty('router', $this->router);
        
        // Mock container
        $container = $this->createMock(\Symfony\Component\DependencyInjection\ContainerInterface::class);
        $container->method('get')
            ->willReturnMap([
                ['form.factory', 1, $this->formFactory],
                ['security.csrf.token_manager', 1, $this->csrfTokenManager],
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

    public function testIndexReturnsCorrectResponse(): void
    {
        // Arrange
        $kategorien = [
            $this->createKategorie(1, 'Kategorie 1'),
            $this->createKategorie(2, 'Kategorie 2'),
        ];

        $this->kategorieRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn($kategorien);

        $this->twig
            ->expects($this->once())
            ->method('render')
            ->with('kategorie/index.html.twig', ['kategories' => $kategorien])
            ->willReturn('<html>Test Response</html>');

        // Act
        $response = $this->controller->index($this->kategorieRepository);

        // Assert
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('<html>Test Response</html>', $response->getContent());
    }

    public function testNewGetRequest(): void
    {
        // Arrange
        $request = new Request();
        $form = $this->createMock(FormInterface::class);
        $formView = $this->createMock(FormView::class);

        $this->formFactory
            ->expects($this->once())
            ->method('create')
            ->with(KategorieType::class, $this->isInstanceOf(Kategorie::class))
            ->willReturn($form);

        $form->expects($this->once())
            ->method('handleRequest')
            ->with($request);

        $form->expects($this->once())
            ->method('createView')
            ->willReturn($formView);

        $this->twig
            ->expects($this->once())
            ->method('render')
            ->with('kategorie/new.html.twig', $this->callback(function ($data) {
                return isset($data['kategorie']) && 
                       $data['kategorie'] instanceof Kategorie &&
                       isset($data['form']) &&
                       $data['form'] instanceof FormView;
            }))
            ->willReturn('<html>New Form</html>');

        // Act
        $response = $this->controller->new($request, $this->entityManager);

        // Assert
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('<html>New Form</html>', $response->getContent());
    }

    public function testNewPostRequestValid(): void
    {
        // Arrange
        $request = new Request();
        $form = $this->createMock(FormInterface::class);

        $this->formFactory
            ->expects($this->once())
            ->method('create')
            ->with(KategorieType::class, $this->isInstanceOf(Kategorie::class))
            ->willReturn($form);

        $form->expects($this->once())
            ->method('handleRequest')
            ->with($request);

        $form->expects($this->once())
            ->method('isSubmitted')
            ->willReturn(true);

        $form->expects($this->once())
            ->method('isValid')
            ->willReturn(true);

        $this->entityManager
            ->expects($this->once())
            ->method('persist')
            ->with($this->isInstanceOf(Kategorie::class));

        $this->entityManager
            ->expects($this->once())
            ->method('flush');

        $this->router
            ->expects($this->once())
            ->method('generate')
            ->with('app_kategorie_index', [])
            ->willReturn('/kategorie');

        // Act
        $response = $this->controller->new($request, $this->entityManager);

        // Assert
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_SEE_OTHER, $response->getStatusCode());
        $this->assertEquals('/kategorie', $response->headers->get('Location'));
    }

    public function testShow(): void
    {
        // Arrange
        $kategorie = $this->createKategorie(1, 'Test Kategorie');

        $this->twig
            ->expects($this->once())
            ->method('render')
            ->with('kategorie/show.html.twig', ['kategorie' => $kategorie])
            ->willReturn('<html>Show Kategorie</html>');

        // Act
        $response = $this->controller->show($kategorie);

        // Assert
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('<html>Show Kategorie</html>', $response->getContent());
    }

    public function testEditGetRequest(): void
    {
        // Arrange
        $request = new Request();
        $kategorie = $this->createKategorie(1, 'Test Kategorie');
        $form = $this->createMock(FormInterface::class);
        $formView = $this->createMock(FormView::class);

        $this->formFactory
            ->expects($this->once())
            ->method('create')
            ->with(KategorieType::class, $kategorie)
            ->willReturn($form);

        $form->expects($this->once())
            ->method('handleRequest')
            ->with($request);

        $form->expects($this->once())
            ->method('createView')
            ->willReturn($formView);

        $this->twig
            ->expects($this->once())
            ->method('render')
            ->with('kategorie/edit.html.twig', [
                'kategorie' => $kategorie,
                'form' => $formView
            ])
            ->willReturn('<html>Edit Form</html>');

        // Act
        $response = $this->controller->edit($request, $kategorie, $this->entityManager);

        // Assert
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('<html>Edit Form</html>', $response->getContent());
    }

    public function testEditPostRequestValid(): void
    {
        // Arrange
        $request = new Request();
        $kategorie = $this->createKategorie(1, 'Test Kategorie');
        $form = $this->createMock(FormInterface::class);

        $this->formFactory
            ->expects($this->once())
            ->method('create')
            ->with(KategorieType::class, $kategorie)
            ->willReturn($form);

        $form->expects($this->once())
            ->method('handleRequest')
            ->with($request);

        $form->expects($this->once())
            ->method('isSubmitted')
            ->willReturn(true);

        $form->expects($this->once())
            ->method('isValid')
            ->willReturn(true);

        $this->entityManager
            ->expects($this->once())
            ->method('flush');

        $this->router
            ->expects($this->once())
            ->method('generate')
            ->with('app_kategorie_index', [])
            ->willReturn('/kategorie');

        // Act
        $response = $this->controller->edit($request, $kategorie, $this->entityManager);

        // Assert
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_SEE_OTHER, $response->getStatusCode());
        $this->assertEquals('/kategorie', $response->headers->get('Location'));
    }

    public function testDeleteWithValidCsrfToken(): void
    {
        // Arrange
        $kategorie = $this->createKategorie(1, 'Test Kategorie');
        $request = new Request();
        $request->setMethod('POST');
        $request->request->set('_token', 'valid_token');

        $this->csrfTokenManager
            ->expects($this->once())
            ->method('isTokenValid')
            ->with($this->callback(function ($token) {
                return $token instanceof CsrfToken && 
                       $token->getValue() === 'valid_token' &&
                       strpos($token->getId(), 'delete') === 0;
            }))
            ->willReturn(true);

        $this->entityManager
            ->expects($this->once())
            ->method('remove')
            ->with($kategorie);

        $this->entityManager
            ->expects($this->once())
            ->method('flush');

        $this->router
            ->expects($this->once())
            ->method('generate')
            ->with('app_kategorie_index', [])
            ->willReturn('/kategorie');

        // Act
        $response = $this->controller->delete($request, $kategorie, $this->entityManager);

        // Assert
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_SEE_OTHER, $response->getStatusCode());
        $this->assertEquals('/kategorie', $response->headers->get('Location'));
    }

    public function testDeleteWithInvalidCsrfToken(): void
    {
        // Arrange
        $kategorie = $this->createKategorie(1, 'Test Kategorie');
        $request = new Request();
        $request->setMethod('POST');
        $request->request->set('_token', 'invalid_token');

        $this->csrfTokenManager
            ->expects($this->once())
            ->method('isTokenValid')
            ->willReturn(false);

        $this->entityManager
            ->expects($this->never())
            ->method('remove');

        $this->entityManager
            ->expects($this->never())
            ->method('flush');

        $this->router
            ->expects($this->once())
            ->method('generate')
            ->with('app_kategorie_index', [])
            ->willReturn('/kategorie');

        // Act
        $response = $this->controller->delete($request, $kategorie, $this->entityManager);

        // Assert
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_SEE_OTHER, $response->getStatusCode());
        $this->assertEquals('/kategorie', $response->headers->get('Location'));
    }

    private function createKategorie(int $id, string $name): Kategorie
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
} 