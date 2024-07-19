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
        //On peut vérifier que les valeurs obtenues correspondent à ce qu'on attend précisément
        $this->assertEquals(1, $results[0]->getId());
        $this->assertEquals('Fido', $results[0]->getName());
        $this->assertEquals('Corgi', $results[0]->getBreed());
        $this->assertInstanceOf(\DateTimeImmutable::class, $results[0]->getBirthDate());

        //Ou bien, on peut faire un test moins mais plus évolutif en testant par exemple les types
        $this->assertIsNumeric($results[0]->getId());
        $this->assertIsString($results[0]->getName());
        $this->assertIsString($results[0]->getBreed());
        $this->assertInstanceOf(\DateTimeImmutable::class, $results[0]->getBirthDate());
    }
}