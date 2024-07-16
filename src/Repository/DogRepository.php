<?php


namespace App\Repository;
use App\Entity\Dog;
use DateTimeImmutable;
use PDO;


class DogRepository {
    private PDO $connection;

    public function __construct() {
        //Création d'une connexion à la base de données, pourrait être externalisé dans une classe à part
        //On utilise des variables d'environnement pour permettre une modification plus propre des
        //informations de connexion à la base de données (qui changeront selon la machine où tourne l'app)
        $this->connection = new PDO(
            'mysql:host='.$_ENV['DATABASE_HOST'].';dbname='.$_ENV['DATABASE_NAME'],
            $_ENV['DATABASE_USER'],
            $_ENV['DATABASE_PASSWORD']
        );
    }
    /**
     * Méthode qui va renvoyer la liste des chiens présents dans la base de données
     * @return \App\Entity\Dog[] un tableau d'instance de la classe Dog
     */
    public function findAll(): array {
        //On prépare et on exécute la requête comme d'habitude
        $query = $this->connection->prepare('SELECT * FROM dog');
        $query->execute();
        $results = $query->fetchAll();
        //Avec les résultats de la requête, on fait en sorte de les convertir en instances de l'entité Dog 
        //plutôt que de renvoyer les données bruts de la base de données, l'idée est d'avoir le reste
        //du code qui ne dépend pas du tout de la base de données
        $list = [];
        foreach($results as $line) {
            $list[] = new Dog(
                $line['name'],
                $line['breed'],
                new DateTimeImmutable($line['birthdate']),
                $line['id']
            );
        }
        return $list;
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
    }
}