<?php

namespace App\Controller;

use App\Repository\CharacterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CharacterController extends AbstractController
{
    #[Route('/characters', name: 'app_character')]
    public function index(CharacterRepository $characterRepository): JsonResponse
    {
        // return $this->json([
        //     'message' => 'Welcome to your new controller!',
        //     'path' => 'src/Controller/CharacterController.php',
        // ]);

        $characters = $characterRepository->findAllCharacters();
        return new JsonResponse($characters, 200);
    }
}
