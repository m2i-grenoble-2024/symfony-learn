<?php


namespace App\Repository;
use App\Entity\Dog;
use DateTimeImmutable;
use Doctrine\DBAL\Connection;


class DogRepository {
    public function __construct(private Connection $connection) {
    }
    /**
     * Méthode qui va renvoyer la liste des chiens présents dans la base de données
     * @return \App\Entity\Dog[] un tableau d'instance de la classe Dog
     */
    public function findAll(): array {
        //On prépare et on exécute la requête comme d'habitude
        $query = $this->connection->prepare('SELECT * FROM dog');
        
        $results=$query->executeQuery();
        //Avec les résultats de la requête, on fait en sorte de les convertir en instances de l'entité Dog 
        //plutôt que de renvoyer les données bruts de la base de données, l'idée est d'avoir le reste
        //du code qui ne dépend pas du tout de la base de données
        $list = [];
        foreach($results->fetchAll() as $line) {
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
        $result = $query->execute();
        if($line = $result->fetch()) {
            return new Dog(
                $line['name'],
                $line['breed'],
                new DateTimeImmutable($line['birthdate']),
                $line['id']
            );
        }
        return null;
    }
    /**
     * Méthode permettant d'ajouter un chien dans la base de données. Il est important d'utiliser les
     * placeholder (:name/:breed/:birthdate) afin d'éviter les injections SQL
     * @param \App\Entity\Dog $dog Une instance de chien à faire persister. On lui assignera un id si le
     * persist fonctionne
     * @return void
     */
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
    /**
     * Met à jour un chien dans la base de données
     * @param \App\Entity\Dog $dog une instance de chien complète avec toutes ses propriétés, dont l'id
     * @return void
     */
    public function update(Dog $dog):void {
        $query = $this->connection->prepare('UPDATE dog SET name=:name,breed=:breed,birthdate=:birthdate WHERE id=:id');
        $query->bindValue(':name', $dog->getName());
        $query->bindValue(':breed', $dog->getBreed());
        $query->bindValue(':birthdate', $dog->getBirthdate()->format('Y-m-d'));
        $query->bindValue(':id',$dog->getId());
        $query->execute();
    }
}