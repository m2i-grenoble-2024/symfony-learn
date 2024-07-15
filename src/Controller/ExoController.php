<?php

namespace App\Controller;

use App\Entity\Dog;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ExoController extends AbstractController {

    #[Route("/calcul/{a}/{b}")]
    public function calcul(int $a, int $b) {
        return $this->json([
            "result" => $a+$b
        ]);
    }

    #[Route("/test-dog")]
    public function testDog() {
        $dog = new Dog();
        $dog->setId(1);
        $dog->setName("Fido");
        $dog->setBreed("Corgi");
        $dog->setBirthdate(new \DateTime());
        return $this->json($dog);
    }

}