<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

final class RegistrierungController extends AbstractController
{
    #[Route('/register', name: 'app_registrierung')]
    public function registrierung(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $registerForm = $this->createFormBuilder()
            ->add('username', TextType::class, [
                'label' => 'Mitarbeiter',
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
                'first_options' => ['label' => 'Passwort'],
                'second_options' => ['label' => 'Passwort wiederholen'],
            ])
            ->getForm();

        $registerForm->handleRequest($request);

        if ($registerForm->isSubmitted() && $registerForm->isValid()) {
            $eingabe = $registerForm->getData();
            
            $existingUser = $entityManager->getRepository(User::class)
                ->findOneBy(['username' => $eingabe['username']]);

            if ($existingUser) {
                $this->addFlash('error', 'Ein Benutzer mit diesem Benutzernamen existiert bereits.');
                return $this->redirectToRoute('app_registrierung');
            }

            $user = new User();
            $user->setUsername($eingabe['username']);
            $user->setPassword($passwordHasher->hashPassword($user, $eingabe['password']));
            
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_index');
        }

        return $this->render('registrierung/index.html.twig', [
            'registerForm' => $registerForm->createView(),
        ]);
    }

}
