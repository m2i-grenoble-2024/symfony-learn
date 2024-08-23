<?php


namespace App\Repository;
use App\Entity\Dog;
use App\Entity\Person;
use PDO;

class PersonRepository {
    private PDO $connection;

    public function __construct() {
        //Création d'une connexion à la base de données, pourrait être externalisé dans une classe à part
        //On utilise des variables d'environnement pour permettre une modification plus propre des
        //informations de connexion à la base de données (qui changeront selon la machine où tourne l'app)
        $this->connection = new PDO(
            'mysql:host='.$_ENV['DATABASE_HOST'].';dbname='.$_ENV['DATABASE_NAME'].';port='.$_ENV['DATABASE_PORT'],
            $_ENV['DATABASE_USER'],
            $_ENV['DATABASE_PASSWORD']
        );
    }
    /**
     * Méthode findAll classique sans particularité. Récupère uniquement les person, pas de chien
     * @return array<Person> La liste des personnes
     */
    public function findAll(): array {
        $query = $this->connection->prepare('SELECT * FROM person');
        $query->execute();
        $results = $query->fetchAll();

        $list = [];
        foreach($results as $line) {
            $person = new Person();
            $person->setId($line['id']);
            $person->setName($line['name']);
            $list[]  = $person;
        }
        return $list;
    }

    /**
     * 
     * Méthode pour aller récupérer toutes les personnes liées à un chien, on se servirait
     * typiquement de cette méthode côté front dans le cas où on afficherait un chien et où
     * on voudrait afficher en dessous ses propriétaires, alors on lancerait ce findByDog pour
     * récupérer les personnes en question.
     * @param int $idDog L'id du chien dont on veut récupérer les personnes
     * @return array<Person> Le tableau des Personnes à qui appartient le chien
     */
    public function findByDog(int $idDog): array {
        $query = $this->connection->prepare('SELECT * FROM person INNER JOIN dog_person ON dog_person.id_person=person.id WHERE id_dog=:idDog');
        $query->bindValue(':idDog', $idDog);
        $query->execute();
        $results = $query->fetchAll();

        $list = [];
        foreach($results as $line) {
            $person = new Person();
            $person->setId($line['id']);
            $person->setName($line['name']);
            $list[]  = $person;
        }
        return $list;
    }
    /**
     * 
     * Exemple d'une méthode qui récupère une personne et tout ses chiens avec une jointure. Sachant que c'est 
     * pas obligé de faire ça et personnelement je recommande plutôt de faire comme la méthode du dessus côté DogRepository,
     * un DogRepository::findByPerson() qui ira chercher tous les chiens liés à une personne et du coup
     * côté front on lancerait les 2 requêtes une pour récupèrer la personne et l'autre pour récupérer ses chiens.
     * Car ici la méthode avec les deux jointures et assez complexe
     * @param int $id id de la personne à récupérer
     * @return ?Person soit la personne avec ses chiens, soit null si pas de person
     */
    public function findById(int $id):?Person {
        $query = $this->connection->prepare('SELECT *, person.name person_name, dog.name dog_name FROM person  INNER JOIN dog_person ON dog_person.id_person=person.id INNER JOIN dog ON dog.id=dog_person.id_dog WHERE id=:id');
        $query->bindValue(':id', $id);
        $query->execute();
        $results = $query->fetchAll();
        $person = null;

        foreach($results as $line) {
            if($person == null) {

                $person = new Person();
                $person->setId($line['id_person']);
                $person->setName($line['person_name']);
            }
            $person->addDog(new Dog(
                $line['dog_name'],
                $line['breed'], 
                new \DateTimeImmutable($line['birthdate']), 
                $line['id_dog'])
            );
        }
        return $person;
    }
    /**
     * Méthode pour assigner un chien à une personne qui viendra insert une nouvelle
     * ligne dans la table de jointure. On pourrait aussi mettre cette méthode dans le
     * DogRepository plutôt qu'ici (mais alors l'appeler assignPerson)
     * @param int $idDog l'id du chien
     * @param int $idPerson l'id de la personne
     * @return void
     */
    public function assignDog(int $idDog, int $idPerson) {
        $query = $this->connection->prepare('INSERT INTO dog_person (id_dog,id_person) VALUES (:idDog, :idPerson)');
        $query->bindValue(':idDog', $idDog);
        $query->bindValue(':idPerson', $idPerson);
        $query->execute();
    }
    /**
     * Méthode qui va faire l'inverse de celle du dessus, à savoir supprimer l'association
     * entre une personne et un chien.On pourrait aussi mettre cette méthode dans le
     * DogRepository plutôt qu'ici (mais alors l'appeler removePerson)
     * @param int $idDog l'id du chien
     * @param int $idPerson l'id de la personne
     * @return void
     */
    public function removeDog(int $idDog, int $idPerson) {
        $query = $this->connection->prepare('DELETE FROM dog_person WHERE id_dog=:idDog AND id_person=:idPerson)');
        $query->bindValue(':idDog', $idDog);
        $query->bindValue(':idPerson', $idPerson);
        $query->execute();
    }
}