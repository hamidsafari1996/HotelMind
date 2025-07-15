<?php

namespace App\Controller;

use App\Entity\Hotel;
use App\Form\HotelType;
use App\Repository\HotelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controller for managing hotel reservations.
 * Provides CRUD operations for hotel entities.
 * This is the main controller for the application's homepage.
 */
#[Route('/')]
final class ReservationController extends AbstractController
{
    /**
     * Displays a list of all hotels.
     * Redirects to login if no hotels are found.
     */
    #[Route(name: 'app_reservation_index', methods: ['GET'])]
    public function index(HotelRepository $hotelRepository): Response
    {
        $hotel = $hotelRepository->findAll();
        if (!$hotel) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('reservation/index.html.twig', [
            'hotels' => $hotelRepository->findAll(),
        ]);
    }

    /**
     * Creates a new hotel entry.
     * Handles both form display and submission, including image upload.
     */
    #[Route('/new', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em)
    {
        $hotel = new Hotel();

        $form = $this->createForm(HotelType::class, $hotel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bild = $request->files->get('hotel')['image'];

            if ($bild) {
                // Process and save the uploaded image
                $dateiname = md5(uniqid()) . '.' . $bild->guessClientExtension();
                
                $bild->move(
                    $this->getParameter('bilder_ordner'),
                    $dateiname
                );

                $hotel->setImage($dateiname);
            }

            $em->persist($hotel);
            $em->flush();

            return $this->redirectToRoute('app_reservation_show', [
                'id' => $hotel->getId()
            ]);            
        }

        return $this->render('reservation/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays details of a specific hotel.
     */
    #[Route('/{id}', name: 'app_reservation_show', methods: ['GET'])]
    public function show(Hotel $hotel): Response
    {
        return $this->render('reservation/show.html.twig', [
            'hotel' => $hotel,
        ]);
    }

    /**
     * Edits an existing hotel entry.
     * Handles both form display and submission, including image replacement.
     */
    #[Route('/{id}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Hotel $hotel, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(HotelType::class, $hotel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                // Remove old image if it exists
                if ($hotel->getImage()) {
                    $oldFilePath = $this->getParameter('bilder_ordner') . '/' . $hotel->getImage();
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }
                // Save new image
                $newFilename = uniqid() . '.' . $imageFile->getClientOriginalExtension();
                $imageFile->move(
                    $this->getParameter('bilder_ordner'),
                    $newFilename
                );
                $hotel->setImage($newFilename);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation/edit.html.twig', [
            'hotel' => $hotel,
            'form' => $form,
        ]);
    }

    /**
     * Deletes a hotel if the CSRF token is valid.
     */
    #[Route('/{id}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Hotel $hotel, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$hotel->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($hotel);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }
}
