<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class FirstController extends AbstractController {
    
    #[Route("/hello")]
    public function hello() {
        return $this->json([
            'message' =>'Coucou tout le monde'
        ]);
        //Le $this->json() fait exactement ce code
        // return new Response(
        //     json_encode(['message' =>'Coucou tout le monde']),
        //      200,
        //     ['Content-Type'=> 'application/json']);
    }
    /**
     * On peut faire une route paramétrée dans laquelle une partie (ou plusieurs)
     * de l'url sera un paramètre qui sera stocké dans une variable du même nom.
     * Ici, on crée un paramètre name qui sera stocké dans la variable $name,
     * cette route sera donc accessible via localhost:8000/hello/jean ou 
     * localhost:8000/hello/test ou localhost:8000/hello/bloup ou n'importe quoi
     * d'autre, et la valeur donnée dans l'url sera récupérable dans la variable
     */
    #[Route("/hello/{name}")]
    public function helloSomeone(string $name) {
        return $this->json(["message" => "Coucou ".$name]);
    }
}