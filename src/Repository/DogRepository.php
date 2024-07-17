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
    /**
     * Méthode qui renvoie une instance de chien si l'id correspond à un chien de la base de données
     * Si pas de correspondance, la méthode renvoie null
     * @param int $id l'id du chien qu'on veut récupérer
     * @return Dog Instance de chien correspondant à l'id ou null si pas de chien
     */
    public function findById(int $id):?Dog {
        $query = $this->connection->prepare('SELECT * FROM dog WHERE id=:id');
        $query->bindValue(':id', $id);
        $query->execute();
        if($line = $query->fetch()) {
            return new Dog(
                $line['name'],
                $line['breed'],
                new DateTimeImmutable($line['birthdate']),
                $line['id']
            );
        }
        return null;
    }

    public function persist(Dog $dog):void {
        $query = $this->connection->prepare('INSERT INTO dog (name,breed,birthdate) VALUES (:name,:breed,:birthdate)');
        $query->bindValue(':name', $dog->getName());
        $query->bindValue(':breed', $dog->getBreed());
        $query->bindValue(':birthdate', $dog->getBirthdate()->format('Y-m-d'));
        $query->execute();
        //On récupère l'id auto incrémenté pour l'assigner au chien qu'on vient de faire persister
        $dog->setId($this->connection->lastInsertId());
    }
    /**
     * Méthode qui supprime un chien par son id
     * @param int $id l'id du chien à supprimer
     * @return void
     */
    public function remove(int $id):void {
        $query = $this->connection->prepare('DELETE FROM dog WHERE id=:id');
        $query->bindValue(':id', $id);
        $query->execute();
    }
}