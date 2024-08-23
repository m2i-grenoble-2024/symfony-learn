# Symfony Learn

## How To Use
1. Cloner le projet
2. Faire un `composer install`
3. Créer une base de données et exécuter le [database.sql](database.sql) dedans
4. Créer un fichier `.env.local` et dedans définir les variable `DATABASE_HOST`, `DATABASE_NAME`, `DATABASE_USER` et `DATABASE_PASSWORD` selon votre base de données
5. Lancer le projet avec `symfony server:start` ou avec F5

## Initialiser un projet + Méthode de travail
Pour commencer un projet Symfony sans utilisé d'ORM (ce qui n'arrivera probablement pas si souvent hors d'un contexte de formation).
* Si on a le Symfony cli, on fait un `symfony new nom-du-projet`
* On installe les dépendances nécessaires : `composer req validator cors serializer` (pour faire respectivement la validation des entités, les CORS pour permettre au front de requêter le back et le serializer pour tranformer les entités en json et inversement)
* On crée une base de données et on crée un fichier database.sql dans lequel on fait nos CREATE TABLE et des INSERT pour avoir une manière de remettre la base de données dans son état initial quand on le souhaite
* On crée un fichier .env.local dans lequel on met les différentes variables d'environnement (voir le how to use) pour se connecter à la base de données

### On fait quoi quand
Une fois le projet et la base de données initialisée, généralement on commence par :
1. Créer les classes entités correspondantes à notre diagramme de classe
2. Créer des classes Repository pour chaque entité
3. Créer des contrôleurs pour chaque entité (pas absolument obligatoire ça en vrai, certaines entités n'auront par forcément de contrôleur, ça dépend)
4. On peut soit travailler par couche, donc faire toutes les méthodes de tous les repositories, et une fois qu'on a terminé, on fait toutes les méthodes de tous les contrôleurs. Soit on peut travailler par tranche et donc faire les méthodes de contrôleur et de repository au fur et à mesure qu'on en a besoin selon la fonctionnalité sur laquelle on est en train de travailler (cette manière de faire peut être un peu moins aliénante et permet d'avoir des fonctionnalités qui marchent au fur et à mesure plutôt que se retrouver à avoir 2-3 couches finies et impecables mais rien qui marche réellement pasqu'il nous manque les autres couches).

## Exercices
### Route Parametrée
1. Créer un nouveau controller ExoController
2. Dans celui ci, créer une route paramétrée avec 2 paramètres de type int sur "/calcul"
3. Dans la méthode associée, faire une addition des 2 paramètres et renvoyer le résultat en mode {"result":10}  si on est allé sur localhost:8000/calcul/4/6

### Des chiens
1. Dans le dossier src, créer un nouveau dossier Entity et dans ce dossier créer un fichier/classe Dog (avec le namespace qui va bien)
2. Rajouter 4 propriétés privées à cette classe, un id en int, un name en string, une breed en string et une birthdate en \DateTime
3. Créer des getter et des setters pour chaque propriété de cette classe (get, fonction qui renvoie la valeur de la propriété, set fonction qui attend un argument et l'assigne à la propriété)
4. Dans le ExoController, faire une nouvelle route /test-dog qui va renvoyer en JSON une instance de chien avec des valeurs en dur

### Un Repository
1. Créer une base de donnéese dam_symfony et exécuter le script database.sql dedans
2. Créer un dossier src/Repository et dans ce dossier créer une nouvelle classe DogRepository
3. Dans cette classe, rajouter une propriété \PDO $connection et un __construct qui va assigner à $this->connection une instance de PDO en lui donnant les informations de connexion à la bdd (username,password,nom de la bdd)
4. Faire une méthode findAll() qui va renvoyer un array et qui va donc faire un prepare d'un select de tous les chiens puis execute puis faire une boucle sur les résultats pour les transformer en instances de la classe Dog
5. Créer un DogController et dans celui ci créer une route /api/dog qui va faire une instance du DogRepository et utiliser son findAll pour renvoyer en json tous les chiens de la bdd

### Persister un chien
1. Dans le DogRepository, créer une nouvelle méthode persist(Dog $dog) (qui va donc attendre un chien en argument)
2. Dans cette méthode, utiliser la connection pour préparer une requête d'INSERT INTO avec des placeholder (les trucs genre :name, :breed etc.)
3. Assigner à ces placeholder les valeurs de l'argument $dog récupérer grâce aux getters (attention, pour la birthdate il faudra la get et lui faire un ->format('y-m-d')) puis executer la requête
4. Dans le DogController créer une nouvelle route sur /api/dog, mais cette fois ci en POST (du coup on va aussi faire en sorte que la première route /api/dog ne marche que pour le GET)
5. On y récupère une instance de Dog grâce au MapRequestPayload comme dans l'exemple, et on donne cette instance de Dog à la méthode persist du Repository
6. On fait un return du dog en json, pourquoi pas avec un status code en 201 (created)

**Bonus:** Dans le persist, faire en sorte de récupérer l'id auto incrémenté pour l'assigner à l'instance de dog, comme ça, une fois que le chien a persisté, on aura un chien complet avec id


### Récupérer un chien
1. Dans le DogRepository, rajouter une méthode findById(int $id) qui va faire une requête SQL pour récupérer un chien par son id
2. Exécuter et faire une instance de chien qu'on return (on peut faire un seul fetch plutôt qu'un fetchAll)
3. Rajouter une nouvelle route dans le DogController qui sera accessible sur /api/dog/1 ou /api/dog/2 par exemple et qui renverra le chien récupéré via le repository
4. Faire que si on ne récupère pas de chien, renvoie une erreur 404

### Le Delete et le Update
1. Faire dans le DogRepository une méthode remove(int $id):void qui va faire une requête SQL de suppression d'un chien par son id
2. Côté contrôleur, on crée une nouvelle route paramétrée sur /api/dog/{id} spécifiquement pour le DELETE et dans celle ci, on récupère le paramètre id et on le donne à manger à notre remove du repository.
3. On peut faire en sorte de renvoyer un 204 (success, no content) et éventuellement avant de supprimer d'appeler la méthode one() du contrôleur pour renvoyer un 404 si le chien n'existe pas (ne marche que si on a fait un throw dans la route pour récupérer un seul chien, sinon on peut juste répéter l'appel au findById et le if pour faire un 404)
4. Retour dans le DogRepository où on rajoute une méthode update(Dog dog):void qui va faire une requête SQL de update d'un chien par son id, qui aura donc 4 placeholders
5. Dans le contrôleur, on crée une route sur /api/dog/{id} en PUT où on va faire un MapRequestPayload pour récupérer le chien dans le body puis lancer la méthode update. (comme dans le delete, il peut être sympa d'utiliser l'id en paramètre pour faire un $this->one($id) pour vérifier si le chien existe avant de le mettre à jour)