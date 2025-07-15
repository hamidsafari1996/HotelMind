<?php

namespace App\Controller\api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Hotel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * API Controller for hotel data.
 * Provides endpoints for accessing hotel information in JSON format.
 * Includes CORS headers for cross-origin requests from localhost:3000.
 */
final class APIController extends AbstractController
{
    /**
     * Handles OPTIONS requests for CORS preflight.
     * Sets appropriate CORS headers to allow cross-origin requests.
     */
    #[Route('/api/hotels', name: 'app_api_options', methods: ['OPTIONS'])]
    public function optionsHotels(): JsonResponse
    {
        $response = new JsonResponse(null, 204);
        $response->headers->set('Access-Control-Allow-Origin', 'http://localhost:3000');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type');
        return $response;
    }

    /**
     * Retrieves all hotels.
     * Returns a JSON array containing all hotel data with their categories.
     */
    #[Route('/api/hotels', name: 'app_api')]
    public function getHotels(EntityManagerInterface $entityManager): JsonResponse
    {
        $hotels = $entityManager->getRepository(Hotel::class)->findAll();

        $data = [];
        foreach ($hotels as $hotel) {
            $data[] = [
                'id' => $hotel->getId(),
                'title' => $hotel->getTitle(),
                'location' => $hotel->getLocation(),
                'image' => $hotel->getImage(),
                'price' => $hotel->getPrice(),
                'days' => $hotel->getDays(),
                'person' => $hotel->getPerson(),
                'info' => $hotel->getInfo(),
                'description' => $hotel->getDescription(),
                'created_at' => $hotel->getCreatedAt()->format('Y-m-d H:i:s'),
                'kategorie' => [
                    'id' => $hotel->getKategorie()->getId(),
                    'name' => $hotel->getKategorie()->getName()
                ]
                ,
                'rating' => $hotel->getRating(),
                'stars' => $hotel->getStars()
            ];
        }

        $response = new JsonResponse($data);
        $response->headers->set('Access-Control-Allow-Origin', 'http://localhost:3000');
        return $response;
    }
    
    /**
     * Retrieves a specific hotel by ID.
     * Returns detailed information about the requested hotel or a 404 error if not found.
     */
    #[Route('/api/hotels/{id}', name: 'app_api_hotel_by_id', methods: ['GET'])]
    public function getHotelById(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $hotel = $entityManager->getRepository(Hotel::class)->find($id);

        if (!$hotel) {
            return new JsonResponse(['error' => 'Hotel not found'], 404);
        }

        $data = [
            'id' => $hotel->getId(),
            'title' => $hotel->getTitle(),
            'location' => $hotel->getLocation(),
            'image' => $hotel->getImage(),
            'price' => $hotel->getPrice(),
            'days' => $hotel->getDays(),
            'person' => $hotel->getPerson(),
            'info' => $hotel->getInfo(),
            'description' => $hotel->getDescription(),
            'created_at' => $hotel->getCreatedAt()->format('Y-m-d H:i:s'),
            'kategorie' => [
                'id' => $hotel->getKategorie()->getId(),
                'name' => $hotel->getKategorie()->getName()
            ],
            'rating' => $hotel->getRating(),
            'stars' => $hotel->getStars()
        ];

        $response = new JsonResponse($data);
        $response->headers->set('Access-Control-Allow-Origin', 'http://localhost:3000');
        return $response;
    }

}
