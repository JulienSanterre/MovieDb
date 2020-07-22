<?php

namespace App\DataFixtures;

use App\Entity\Casting;
use App\Entity\Genre;
use App\Entity\Movie;
use App\Entity\Person;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/** 
 * Correction Challenge S02E09
 * Option 1, On crée des Fixtures manuellement.
 * 
 */
class ManuelFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // On crée un nouvel objet Movie, on lui donne un titre et on le persist
        $movie = new Movie();
        $movie->setTitle('Shadowlands');
        $manager->persist($movie);

        // On crée un Genre et on va l'associer à notre Movie
        $genre = new Genre();
        $genre->setName('Biographie');
        // On associe le genre à notre film
        $genre->addMovie($movie);
        $manager->persist($genre);

        $person = new Person();
        $person->setName('Anthony Hopkins');
        $manager->persist($person);
        
        $casting = new Casting();
        $casting->setCreditOrder(1);
        $casting->setRole('Jack Lewis');
        $casting->setMovie($movie);
        $casting->setPerson($person);
        $manager->persist($casting);

        // Comme ndas un contrôleur, on flush après avoir persisté
        $manager->flush();
    }
}
