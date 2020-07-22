<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * On aurait très bien pu se servir de ce fichier,
 * il s'est installé tout seul à partir de l'installation
 * de DoctrineFixturesbundle.
 * 
 * On le laisse en exemple ici.
 * 
 */
class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
