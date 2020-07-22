# Atelier Admin  

Réaliser la partie back-offfice de MovieDB.

- Etudier le MCD existant et implémenter les structures manquantes.
- Rajouter les éléments nécessaires à l'administration.
- Créer les contrôleurs nécessaires.
- Configurer les routes, les actions (méthodes de contrôleurs).
- Créer et implementer les formulaires nécessaires.
- Implémenter les fonctionnalités attendues.

## Etat des lieux

Il y a actuellement :
- Les entités : `Genre`, `Movie`, `Casting`, `Person`
- La partie front : liste des films (home) et page détail d'un film accessible à partir de la home.

Ce qu'il manque :

- Une partie d'administration du contenu existant.
- La fin de la modélisation de la base de données [de ce schéma](https://github.com/O-clock-Alumni/fiches-recap/blob/master/symfony/themes/img/mcd-moviedb-full.png).
- _S'authentifier pour accèder à la partie back-office_ : **A ne pas faire aujourd'hui !**

Les notions que nous avons abordées jusqu'à présent :

- Les routes
- Les contrôleurs
- Les vues
- Les entités & associations
- Les fixtures
- Les formulaires

## Objectifs

### Mise à jour des entités

- Terminer [l'implémentation du MCD](https://github.com/O-clock-Alumni/fiches-recap/blob/master/symfony/themes/img/mcd-moviedb-full.png), soit les entités :
    - `Job`
    - `Department`
    - la table de jointure `Team`

> Note : Pour la table Team le principe est le même que celui effectué avec Casting

### Interface d'admin

Créer une "interface d'admin" permettant de _lister/ajouter/modifier/supprimer_ :  

- Department
- Job _lié à Department via le formulaire_
- Movie _lié à Genre via le formulaire_
- Casting
- _Team (à faire en bonus mais pas tout de suite, voir plus bas)._

### Navigation

- Global : Prévoir une navigation principale qui contiendra un lien "admin" permettant d'accèder à la liste des films du back-office.
- Admin : Prévoir une navigation qui contiendra un lien pour chaque section à administrer. Cette navigation doit être prise en compte **en plus** de la navigation principale.

### Contrôleurs

- Chaque entité doit avoir un contrôleur dédié.
- Séparer les contrôleurs dédiés au back-office (BO) de ceux existants dédiés au front (vous pouvez [vous inspirer de cet exemple](https://symfony.com/doc/current/best_practices/controllers.html#routing-configuration)).
- Les routes, leurs noms et les méthodes autorisées sur celles-ci doivent être cohérentes lorsque l'on [visualise l'ensemble des routes](https://symfony.com/doc/current/routing/debug.html).

> Best practices ❤ : Pour se faciliter la vie avec les routes vous pouvez utiliser le [préfixe de contrôleur](https://symfony.com/blog/new-in-symfony-4-1-prefix-imported-route-names).

- Mettre les [requirements](https://symfony.com/doc/current/routing/requirements.html) sur les routes avec paramètres.
- Rajouter des *flash messages* suite aux actions d'ajout.
- Effectuer des *redirections* dans les actions où cela s'avère nécessaire.

#### Bonus contrôleurs

- Afficher les *flash messages* à l'aide d'une classe CSS cohérente. Effectuer aussi cet ajout pour les actions d'update et de delete.
- Gérer des messages d'erreur custom pour les erreurs 404 (not found) lors de la consultation du détail d'un élément (au moins show, idéalement dès qu'il y a un `id` dans la route).

### Formulaires

- Utiliser la commande `make:form`
> Note : Si vous rencontrez une erreur du genre `cannot convert object to string`, lors des relations entre les entités, vous devez implémenter la méthode _toString()_ sur l'entité concernée. [Exemple ici](https://openclassrooms.com/forum/sujet/symfony2-error-could-not-be-converted-to-string).
- Appliquer les types d'input adéquats.
> Note : Même si le "Field Type Guessing" peut parfois dégrossir le travail, il est néanmoins préférable de garder la main sur le type affiché car celui ci reste limité (int, string, text, etc.) et non paramétrable directement. **Prenez donc quelques secondes pour définir le type de votre champ manuellement.**
- Appliquer les [contraintes de validation](https://symfony.com/doc/current/reference/constraints.html) sur les champs.
> Note : Directement dans le fichier FormType **ou** en annotation (il faut choisir son camp ;p).
- Appliquer le thème Bootstrap sur les formulaires.


#### Bonus 

- _Formule 2 en 1_ : tenter de centraliser les fonctions d'ajout/update qui utilisent le même formulaire et la même logique, au niveau des contrôleurs.

### Fonctionnalités supplémentaires

> Vous êtes libre de mettre en place tout ou partie de ces fonctionnalités comme & dans l'ordre ou vous le souhaitez.

- Mettre en place les fixtures manquantes.
- Permettre à l'utilisateur d'accèder aux 10 derniers films saisis (sur la page d'accueil ou sur une autre page) => _via une requête custom_.
- Permettre [l'ajout d'une image](https://symfony.com/doc/current/controller/upload_file.html) sur un film afin de pouvoir l'afficher sur la home et la page de détail d'un film.

### Bonus (facultatif)

> Note : Vous pouvez mettre en place et au choix l'un ou plusieurs des bonus suivants

- Permettre à l'utilisateur de pouvoir effectuer une recherche sur un titre film complet ou partiel. 3 caractères seront attendus au minimum pour pouvoir lancer une recherche.
- Mettre en place [un template custom](https://symfony.com/doc/current/controller/error_pages.html) pour les messages d'erreur 404.
- Tenter de mettre en place de la pagination sur la homepage, [manuellement](https://www.google.fr/search?q=doctrine+paginator) ou [à l'aide d'un bundle](https://github.com/KnpLabs/KnpPaginatorBundle) de ce type.
- Interface d'admin pour l'entité `Team`.
- Gérer les formats des posters de films : Home, page de détail et miniatures dans la liste du back-office [grâce au bundle LiipImagine](https://symfony.com/doc/2.0/bundles/LiipImagineBundle/index.html)


### Bonus expérimentation (facultatif)

- Effectuer un test de la commande `make:crud`
    - Même si à première vue cela semble terriblement efficace : Pas de customisation possible en l'état actuel (besoin de déplacer et de modifier le code généré).
    - Essayer de voir comment mettre en place un début de [maker custom](https://symfony.com/doc/current/bundles/SymfonyMakerBundle/index.html).