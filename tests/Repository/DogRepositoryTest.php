<?php

namespace App\Tests\Repository;
use App\Entity\Dog;
use App\Repository\DogRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DogRepositoryTest extends KernelTestCase {
    


    public function testFindAllShouldReturnDogs() {
        self::bootKernel();
        $repo = self::getContainer()->get(DogRepository::class);
        $results = $repo->findAll();
        //On fait l'assertion que le tableau de résultat n'est pas sensé être vide
        $this->assertNotEmpty($results);
        //On vérifie que le tableau ne contient que des instances de l'entité Dog
        $this->assertContainsOnlyInstancesOf(Dog::class,  $results);

        $this->assertEquals('Fido', $results[0]->getName());
        $this->assertEquals('Corgi', $results[0]->getBreed());
    }
}