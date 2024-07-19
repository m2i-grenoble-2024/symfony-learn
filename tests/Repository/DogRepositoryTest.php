<?php

namespace App\Tests\Repository;
use App\Entity\Dog;
use App\Repository\DogRepository;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DogRepositoryTest extends KernelTestCase {
    private DogRepository $repo;
    /**
     * La méthode setUp sera relancer avant chacun des tests de la classe, ici on
     * va s'en servir pour créer notre instance de DogRepository et pour remettre
     * la base de données à zéro pour faire qu'on puisse relancer les tests autant
     * de fois qu'on souhaite dans un environnement contrôlé et reproductible
     */
    public function setUp(): void {
        self::bootKernel();
        $this->repo = self::getContainer()->get(DogRepository::class);
        //Remettre la base de donnée à zéro
        $connection = self::getContainer()->get(Connection::class);
        $connection->exec(file_get_contents('database.sql'));
    }

    public function testFindAllShouldReturnDogs() {
        $results = $this->repo->findAll();

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

    //Faire 2 scénarios de test pour le findById un pour un chien qui existe, l'autre pour
    //un chien qui n'existe pas (1 scénario = une méthode)

    public function testFindByIdShouldReturnExistingDog() {
        $result = $this->repo->findById(1);
        $this->assertNotNull($result);

        $this->assertEquals(1, $result->getId());
        $this->assertEquals('Fido', $result->getName());
        $this->assertEquals('Corgi', $result->getBreed());
        $this->assertInstanceOf(\DateTimeImmutable::class, $result->getBirthDate());

    }
    public function testFindByIdShouldReturnNullIfNoDog() {
        $result = $this->repo->findById(100);
        $this->assertNull($result);

    }

    //Faire un test pour le persist, où on crée un chien de test et on le donne à manger
    //à la méthode et on vérifie si on a un id dans le chien

    public function testPersistDogInDatabase() {

        $dog = new Dog('name test','breed test',new \DateTimeImmutable('2020-01-01'));
        $this->repo->persist($dog);
        //On peut soit vérifier que le chien a bien un id
        $this->assertNotNull($dog->getId());
        //ou bien tester l'id spécifique si on est sûr de notre environnement de test et des data présentes
        $this->assertEquals(5, $dog->getId());
    }
}