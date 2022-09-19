<?php

namespace App\Controller;

use Throwable;
use App\Entity\Personnage;
use App\Repository\PersonnageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/personnages', name: 'api_')]
class PersonnageController extends AbstractController
{
    #[Route('', name: 'app_personnage_index', methods: ['GET'])]
    public function index(PersonnageRepository $personnageRepository): JsonResponse
    {
        $personnages = $personnageRepository->findAll();

        return $this->json([
            $personnages
        ]);

        // $personnages = $personnageRepository->findAllpersonnages();
        // return new JsonResponse($personnages, 200);
    }

    #[Route('/new', name: 'app_personnage_new', methods: ['POST'])]
    public function new(Request $request, SerializerInterface $serializer,  PersonnageRepository $personnageRepository): JsonResponse
    {

        $personnage = $serializer->deserialize($request->getContent(), Personnage::class, 'json');
        try {
            $personnageRepository->add($personnage, true);
        } catch (Throwable $th) {
            return $this->json([
                'message' => 'Something went wrong.'
            ], 422);
        }

        return $this->json([
            'message' => 'New character was created successfully.'
        ], 201);
    }
}
