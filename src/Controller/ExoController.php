<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ExoController extends AbstractController {

    #[Route("/calcul/{a}/{b}")]
    public function calcul(int $a, int $b) {
        return $this->json([
            "result" => $a+$b
        ]);
    }

}