<?php

namespace App\Controller;

use App\Repository\DogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class DogController extends AbstractController
{
    #[Route('/api/dog')]
    public function all(): JsonResponse
    {
        //On fait une instance de notre DogRepository et on se sert du findAll pour le renvoyer au
        //format JSON
        $repo = new DogRepository();
        return $this->json(
            $repo->findAll()
        );
    }
}
