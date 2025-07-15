<?php

namespace App\Controller;

use App\Entity\Kategorie;
use App\Form\KategorieType;
use App\Repository\KategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controller for managing Kategorie entities.
 * Provides CRUD operations for categories.
 */
#[Route('/kategorie')]
final class KategorieController extends AbstractController
{
    /**
     * Displays a list of all categories.
     */
    #[Route(name: 'app_kategorie_index', methods: ['GET'])]
    public function index(KategorieRepository $kategorieRepository): Response
    {
        return $this->render('kategorie/index.html.twig', [
            'kategories' => $kategorieRepository->findAll(),
        ]);
    }

    /**
     * Creates a new category.
     * Handles both the form display and submission.
     */
    #[Route('/new', name: 'app_kategorie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $kategorie = new Kategorie();
        $form = $this->createForm(KategorieType::class, $kategorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($kategorie);
            $entityManager->flush();

            return $this->redirectToRoute('app_kategorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('kategorie/new.html.twig', [
            'kategorie' => $kategorie,
            'form' => $form,
        ]);
    }

    /**
     * Displays details of a specific category.
     */
    #[Route('/{id}', name: 'app_kategorie_show', methods: ['GET'])]
    public function show(Kategorie $kategorie): Response
    {
        return $this->render('kategorie/show.html.twig', [
            'kategorie' => $kategorie,
        ]);
    }

    /**
     * Edits an existing category.
     * Handles both the form display and submission.
     */
    #[Route('/{id}/edit', name: 'app_kategorie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Kategorie $kategorie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(KategorieType::class, $kategorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_kategorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('kategorie/edit.html.twig', [
            'kategorie' => $kategorie,
            'form' => $form,
        ]);
    }

    /**
     * Deletes a category if the CSRF token is valid.
     */
    #[Route('/{id}', name: 'app_kategorie_delete', methods: ['POST'])]
    public function delete(Request $request, Kategorie $kategorie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$kategorie->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($kategorie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_kategorie_index', [], Response::HTTP_SEE_OTHER);
    }
}
