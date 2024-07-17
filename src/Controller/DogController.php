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
    /**
     * Permet de laisser symfony instancier le DogRepository pour toute la classe, on pourra
     * y accéder dans chaque méthode avec $this->repo
     */
    public function __construct(private DogRepository $repo){}
    
    #[Route(methods: 'GET')]
    public function all(): JsonResponse
    {
        //On fait une instance de notre DogRepository et on se sert du findAll pour le renvoyer au
        //format JSON

        return $this->json(
            $this->repo->findAll()
        );
    }

    #[Route('/{id}', methods:'GET')]
    public function one(int $id): JsonResponse {
        
        $dog = $this->repo->findById($id);
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

        $this->repo->persist($dog);
        return $this->json($dog, 201);
 
    }
    #[Route('/{id}', methods: 'DELETE')]
    public function delete(int $id): JsonResponse {
        //On appelle la méthode one définit au dessus pour faire que si le chien n'existe pas, un 404 sera renvoyé
        $this->one($id);
        $this->repo->remove($id);
        return $this->json(null, 204);
    }
    #[Route('/{id}', methods:'PUT')]
    public function put(int $id, #[MapRequestPayload] Dog $dog):JsonResponse {
        $this->one($id);
        //On assigne l'id en paramètre au chien récupéré dans le body pour être bien sûr qu'ils correspondent
        $dog->setId($id);
        $this->repo->update($dog);
        return $this->json($dog);
    }
}
