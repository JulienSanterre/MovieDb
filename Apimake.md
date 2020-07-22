# Création d'une API pour MovieDB

## Conceptualisation

On a besoin de déterminer à l'avance l'objectif de notre API. On va donc lister les fonctionnalités, ce qui va nous permettre de déterminer les routes à créer.

### Fonctionnalités
- Lister les Movies
- Afficher les détails d'un Movie avec toutes ses données, son casting, ses genres, ses Teams
- Afficher les détails d'un Genre ?
- Afficher les détails d'une Person
- Afficher les Job avec leur Department et les Person qui y sont reliés (Team)
- Lister les Department
- Afficher les détails d'un Department avec ses Jobs

### Routes
À partir des fonctionnalités, on peut déterminer les routes !
**Elles sont préfixées par /api**

GET /movies
api_movies_list
Liste tous les Movies : afficher l'id et le titre de chacun avec un lien vers api_movies_single
```json
[
    {
    
        "id": 1,
        "title": "Titre du film",
        "url": "http://localhost:8000/api/movies/1"
    },
    …
]
```

GET /movies/{id}
api_movies_single
Affiche les détails d'un Movie avec toutes ses propriétés.
On affiche également le casting du film, pour chacun des Casting on affiche le rôle, le nom de l'acteur, l'id de l'acteur et le lien pour voir l'acteur (Person)
On affiche le genre avec l'id et le name
On affiche les teams, avec le nom du job, l'id du job, le nom de la person et son id

```json
{
    "id": 1,
    "title": "Titre du film",
    "createdAt": "2019-11-12 11:10:45",
    "updatedAt": "2019-11-12 11:12:17",
    "genres": [
        {
            "id": 34,
            "name": "Horreur"
        },
        {
            "id": 18,
            "name": "Thriller"
        },
        …
    ],
    "castings": [
        {
            "role": "Jean-Claude",
            "person": {
                "id": 22,
                "name": "Ginette Larue",
                "url": "http://localhost:8000/api/person/22"
            }
        },
        …
    ],
    "teams": [
        {
            "person": {
                "id": 78,
                "name": "Jean-Claude Duche",
            },
            "job": {
                "id": 474,
                "name": "Cadreur",
            }
        },
        …
    ],
    "url": "http://localhost:8000/api/movies/1"
}
```