<?php


namespace App\Repository;
use App\Entity\Dog;
use DateTime;
use PDO;


class DogRepository {
    private PDO $connection;

    public function __construct() {
        //Création d'une connexion à la base de données, pourrait être externaliser dans une classe à part
        $this->connection = new PDO('mysql:host=localhost;dbname=dam_symfony', 'dev', '1234');
    }
    /**
     * Méthode qui va renvoyer la liste des chiens présents dans la base de données
     * @return \App\Entity\Dog[] un tableau d'instance de la classe Dog
     */
    public function findAll(): array {
        $query = $this->connection->prepare('SELECT * FROM dog');
        $query->execute();
        $results = $query->fetchAll();

        $list = [];
        foreach($results as $line) {
            $list[] = new Dog(
                $line['name'],
                $line['breed'],
                new DateTime($line['birthdate']),
                $line['id']
            );
        }
        //Une manière "différente" de faire exactement la même chose mais avec des fetch au lieu d'un fetchAll
        /*
        while($line = $query->fetch()) {

            $list[] = new Dog(
                $line['name'],
                $line['breed'],
                new DateTime($line['birthdate']),
                $line['id']
            );
            
        }
        */
        return $list;
    }
}