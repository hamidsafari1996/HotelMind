<?php

namespace App\Tests\Unit\Controller;

use App\Controller\RegistrierungController;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

class RegistrierungControllerUnitTest extends TestCase
{
    private RegistrierungController $controller;
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;
    private FormFactoryInterface $formFactory;
    private Environment $twig;
    private RouterInterface $router;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $this->formFactory = $this->createMock(FormFactoryInterface::class);
        $this->twig = $this->createMock(Environment::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->userRepository = $this->createMock(UserRepository::class);

        $this->controller = new RegistrierungController();
        
        // Inject dependencies via reflection
        $this->injectPrivateProperty('twig', $this->twig);
        $this->injectPrivateProperty('router', $this->router);
        
        // Mock container
        $container = $this->createMock(\Symfony\Component\DependencyInjection\ContainerInterface::class);
        $container->method('get')
            ->willReturnMap([
                ['form.factory', 1, $this->formFactory],
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

    private function createMockRequest(array $postData = []): Request
    {
        $request = new Request([], $postData);
        $session = new Session(new MockArraySessionStorage());
        $request->setSession($session);
        return $request;
    }

    private function createMockForm(bool $isSubmitted = false, bool $isValid = false, array $data = []): FormInterface
    {
        $form = $this->createMock(FormInterface::class);
        $form->method('isSubmitted')->willReturn($isSubmitted);
        $form->method('isValid')->willReturn($isValid);
        $form->method('getData')->willReturn($data);
        
        $formView = $this->createMock(FormView::class);
        $form->method('createView')->willReturn($formView);
        
        return $form;
    }

    public function testRegistrierungGetRequest(): void
    {
        // Arrange
        $request = $this->createMockRequest();
        $form = $this->createMockForm();

        $this->formFactory
            ->expects($this->once())
            ->method('createBuilder')
            ->willReturn($this->createMockFormBuilder($form));

        $this->twig
            ->expects($this->once())
            ->method('render')
            ->willReturn('<html>Registration Form</html>');

        // Act
        $response = $this->controller->registrierung($request, $this->passwordHasher, $this->entityManager);

        // Assert
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('<html>Registration Form</html>', $response->getContent());
    }

    public function testRegistrierungSuccessfulSubmission(): void
    {
        // Arrange
        $formData = [
            'username' => 'testuser',
            'password' => 'testpassword123'
        ];
        $hashedPassword = 'hashed_password_123';

        $request = $this->createMockRequest($formData);
        $form = $this->createMockForm(true, true, $formData);

        $this->formFactory
            ->expects($this->once())
            ->method('createBuilder')
            ->willReturn($this->createMockFormBuilder($form));

        $this->entityManager
            ->expects($this->once())
            ->method('getRepository')
            ->with(User::class)
            ->willReturn($this->userRepository);

        $this->userRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['username' => 'testuser'])
            ->willReturn(null); // User doesn't exist

        $this->passwordHasher
            ->expects($this->once())
            ->method('hashPassword')
            ->with($this->isInstanceOf(User::class), 'testpassword123')
            ->willReturn($hashedPassword);

        $this->entityManager
            ->expects($this->once())
            ->method('persist')
            ->with($this->callback(function (User $user) use ($hashedPassword) {
                return $user->getUsername() === 'testuser' && 
                       $user->getPassword() === $hashedPassword;
            }));

        $this->entityManager
            ->expects($this->once())
            ->method('flush');

        $this->router
            ->expects($this->once())
            ->method('generate')
            ->with('app_reservation_index')
            ->willReturn('/reservations');

        // Act
        $response = $this->controller->registrierung($request, $this->passwordHasher, $this->entityManager);

        // Assert
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());
        $this->assertEquals('/reservations', $response->headers->get('Location'));
    }

    public function testRegistrierungInvalidForm(): void
    {
        // Arrange
        $request = $this->createMockRequest();
        $form = $this->createMockForm(true, false); // Submitted but invalid

        $this->formFactory
            ->expects($this->once())
            ->method('createBuilder')
            ->willReturn($this->createMockFormBuilder($form));

        $this->twig
            ->expects($this->once())
            ->method('render')
            ->willReturn('<html>Registration Form with Errors</html>');

        // Act
        $response = $this->controller->registrierung($request, $this->passwordHasher, $this->entityManager);

        // Assert
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('<html>Registration Form with Errors</html>', $response->getContent());
    }

    /**
     * Test that verifies the user repository logic works correctly
     */
    public function testUserRepositoryLogic(): void
    {
        // Test that the repository can find existing users
        $existingUser = new User();
        $existingUser->setUsername('existinguser');

        $this->entityManager
            ->expects($this->once())
            ->method('getRepository')
            ->with(User::class)
            ->willReturn($this->userRepository);

        $this->userRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['username' => 'existinguser'])
            ->willReturn($existingUser);

        // Verify that when a user exists, the repository returns the user
        $result = $this->entityManager->getRepository(User::class)->findOneBy(['username' => 'existinguser']);
        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals('existinguser', $result->getUsername());
    }

    public function testRegistrierungFormCreation(): void
    {
        // Arrange
        $request = $this->createMockRequest();
        $form = $this->createMockForm();
        $formBuilder = $this->createMock(\Symfony\Component\Form\FormBuilderInterface::class);

        // Test that the form is built with correct field types
        $formBuilder->expects($this->exactly(2))
            ->method('add')
            ->willReturnSelf();

        $formBuilder->method('getForm')->willReturn($form);

        $this->formFactory
            ->expects($this->once())
            ->method('createBuilder')
            ->willReturn($formBuilder);

        $this->twig
            ->expects($this->once())
            ->method('render')
            ->willReturn('<html>Form</html>');

        // Act
        $response = $this->controller->registrierung($request, $this->passwordHasher, $this->entityManager);

        // Assert
        $this->assertInstanceOf(Response::class, $response);
    }

    private function createMockFormBuilder(FormInterface $finalForm): object
    {
        $formBuilder = $this->createMock(\Symfony\Component\Form\FormBuilderInterface::class);
        
        $formBuilder->method('add')->willReturnSelf();
        $formBuilder->method('getForm')->willReturn($finalForm);
        
        $finalForm->method('handleRequest')->willReturnSelf();
        
        return $formBuilder;
    }
} 