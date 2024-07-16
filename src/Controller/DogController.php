<?php

namespace App\Controller;

use App\Entity\Dog;
use App\Repository\DogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/dog')]
class DogController extends AbstractController
{
    
    #[Route(methods: 'GET')]
    public function all(): JsonResponse
    {
        //On fait une instance de notre DogRepository et on se sert du findAll pour le renvoyer au
        //format JSON
        $repo = new DogRepository();
        return $this->json(
            $repo->findAll()
        );
    }

    #[Route(methods:'POST')]
    public function add(#[MapRequestPayload] Dog $dog): JsonResponse {
        $repo = new DogRepository();
        $repo->persist($dog);
        return $this->json($dog, 201);
    }
}
