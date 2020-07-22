<?php

namespace App\DataFixtures;

use App\DataFixtures\MovieDbNativeLoader;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

use App\Service\Slugger;
use App\Entity\Movie;

class NelmioAliceFixtures extends Fixture
{

    private $slugger;

    public function __construct(Slugger $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager)
    {
        // Malgré la config dans config/packages/dev/nelmio_alice.yaml
        // On est obligé d'ajouter cette ligne (et son use) pour avoir des résultats en français :
        // $faker = Factory::create('fr_FR');

        // On utilise le générateur de Faker qu'on met en argument du constructeur de NativeLoader
        $loader = new MovieDbNativeLoader(/* $faker */);
        
        // Importe le fichier de fixtures et récupère les entités générés
        $entities = $loader->loadFile(__DIR__.'/fixtures.yaml')->getObjects();
        
        // Persiste chacun des objets à enregistrer en BDD
        foreach ($entities as $entity) {
            // $entity peut être n'importe quelle entité créée grâcé à NelmioAlice
            // On test si cette entité est de la classe Movie
            if ($entity instanceof Movie) {
                $this->slugger->slugifyMovie($entity);
            }
            
            $manager->persist($entity);
        };
        
        // Flush pour exécuter les requêtes SQL
        $manager->flush();
    }
}
