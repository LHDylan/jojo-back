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
use Symfony\Component\HttpFoundation\Response;

#[Route('/api/personnages', name: 'api_')]
class PersonnageController extends AbstractController
{
    #[Route('', name: 'app_personnage_index', methods: ['GET'])]
    public function index(PersonnageRepository $personnageRepository): JsonResponse
    {
        $personnages = $personnageRepository->findAll();

        return $this->json([
            'personnages' => $personnages
        ]);
    }

    #[Route('/{id}', name: 'app_personnage_show', methods: ['GET'])]
    public function show(Personnage $personnage, PersonnageRepository $personnageRepository, int $id): JsonResponse
    {
        $personnage = $personnageRepository->findOneBy(['id' => $id]);

        $data = [
            'id' => $personnage->getId(),
            'name' => $personnage->getName(),
            'stand' => $personnage->getStand(),
            'birthday' => $personnage->getBirthday(),
            'gender' => $personnage->getGender(),
            'height' => $personnage->getHeight(),
            'weight' => $personnage->getWeight(),
            'anime' => $personnage->getAnime(),
            'description' => $personnage->getDescription(),
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/', name: 'app_personnage_new', methods: ['POST'])]
    public function new(Request $request, SerializerInterface $serializer, PersonnageRepository $personnageRepository): JsonResponse
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

    #[Route('/{id}', name: 'app_personnage_edit', methods: ['PUT'])]
    public function edit(Request $request, PersonnageRepository $personnageRepository, int $id): JsonResponse
    {
        $personnage = $personnageRepository->findOneBy(['id' => $id]);
        $data = json_decode($request->getContent(), true);

        empty($data['name']) ? true : $personnage->setName($data['name']);
        empty($data['stand']) ? true : $personnage->setStand($data['stand']);
        empty($data['birthday']) ? true : $personnage->setBirthday(new \DateTime($data['birthday']));
        empty($data['gender']) ? true : $personnage->setGender($data['gender']);
        empty($data['height']) ? true : $personnage->setHeight($data['height']);
        empty($data['weight']) ? true : $personnage->setWeight($data['weight']);
        empty($data['anime']) ? true : $personnage->setAnime($data['anime']);
        empty($data['description']) ? true : $personnage->setDescription($data['description']);

        $personnageRepository->add($personnage, true);

        // return new JsonResponse($personnage, Response::HTTP_OK);

        return $this->json([
            'personnage' => $personnage
        ]);
    }

    #[Route('/{id}', name: 'app_personnage_delete', methods: ['DELETE'])]
    public function delete(PersonnageRepository $personnageRepository, int $id): JsonResponse
    {
        $personnage = $personnageRepository->findOneBy(['id' => $id]);

        $personnageRepository->remove($personnage, true);

        return $this->json([
            'message' => 'Personnage deleted'
        ]);
    }
}
