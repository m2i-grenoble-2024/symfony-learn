<?php

namespace App\Controller;

use App\Entity\Dog;
use App\Repository\DogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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

    #[Route('/{id}', methods:'GET')]
    public function one(int $id): JsonResponse {
        $repo = new DogRepository();
        $dog = $repo->findById($id);
        if(!$dog) {
            throw new NotFoundHttpException("Dog not found");
            //ou bien ça, fondamentalement, c'est à peu près la même chose
            //return $this->json('Dog not found', 404);
            
        }
        return $this->json($dog);
    }

    #[Route(methods:'POST')]
    public function add(#[MapRequestPayload] Dog $dog): JsonResponse {
        // if(empty($dog->getName()) || empty($dog->getBreed())) {} //On pourrait faire de la validation sur notre chien ici
        $repo = new DogRepository();
        $repo->persist($dog);
        return $this->json($dog, 201);
    }
}
