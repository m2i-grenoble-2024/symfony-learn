# Symfony Learn

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