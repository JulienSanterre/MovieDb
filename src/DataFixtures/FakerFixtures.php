<?php

namespace App\DataFixtures;

use App\DataFixtures\Faker\MovieAndGenreProvider;
use Faker\Factory;
use Faker\ORM\Doctrine\Populator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Correction Challenge S02E09
 * Option 2, on utilise Faker pour charger des données dans la BDD
 * 
 * Faker est une librairie capable de créer des données au hasard.
 * On peut s'en servir partout, juste pour afficher des fausses données en front par exemple.
 * Ici on utilise le Populator qui vient avec et qui sert à charger des données avec Doctrine.
 * 
 */
class FakerFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Pour utilsier Faker, on besoin d'un générateur à partir du Factory
        $generator = Factory::create('fr_FR');

        // Le générateur de base est celui de Faker
        // On a créé un Provider spécifique pour notre projet (MovieAndGenreProvider)
        // Il faut associer de provider au générateur
        $generator->addProvider(new MovieAndGenreProvider($generator));

        // On a aussi besoin du populator
        // On l'associe au générateur à l'entitymanager
        $populator = new Populator($generator, $manager);

        $populator->addEntity('App\Entity\Movie', 10, [
            'title' => function() use ($generator) { return $generator->unique()->movieTitle(); }
        ]);

        $populator->addEntity('App\Entity\Genre', 20, array(
            'name' => function() use ($generator) { return $generator->unique()->movieGenre(); },
        ));
        
        $populator->addEntity('App\Entity\Person', 20, array(
            'name' => function() use ($generator) { return $generator->name(); },
        ));
        
        $populator->addEntity('App\Entity\Casting', 50, array(
            'creditOrder' => function() use ($generator) { return $generator->numberBetween(1, 10); },
            'role' => function() use ($generator) { return $generator->firstName(); },
        ));

        $inserted = $populator->execute();

        // On a un souci avec les relations ManyToMany, Faker ne sait pas les gérer correctement !
        // On va devoir utiliser nous même le manager et les entités générées pour créer ces relations
        // La méthode ->execute() retourne la liste des entités créées, on peut donc s'en servir pour les modifier les persister par la suite
        
        // On récupère les Movie et les Genre pour les associer entre eux
        $movies = $inserted['App\Entity\Movie'];
        $genres = $inserted['App\Entity\Genre'];

        // On boucle sur tous les movies et pour chacun on associe trois genre au hasard
        foreach($movies as $movie) {
            // On mélange la liste des genres
            shuffle($genres);

            $movie->addGenre($genres[0]);
            $movie->addGenre($genres[1]);
            $movie->addGenre($genres[2]);

            // Contrairement à l'entity manager qu'on utilise ailleurs dans le code, ici on persiste tout ce qu'on crée ET tout ce qu'on modifie
            $manager->persist($movie);
        }

        // On flush pour tout enregistrer
        $manager->flush();
    }
}
