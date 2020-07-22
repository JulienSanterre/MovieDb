<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RoutesAvailabilityTest extends WebTestCase
{
    /**
     * On crée une méthode qui va tester un ensemble de liens
     * Elle ne vérifie que le succès du chargement de chaque lien
     * On relie cette liste de liens avec notre méthode grâce à l'annotation suivante
     * Pour tester chacun des url, on ajoute $url en paramètre de notre méthode
     * 
     * @dataProvider urlAnonymousProvider
     */
    public function testAnonymousRoutes($url)
    {
        $client = static::createClient();
        $crawler = $client->request('GET', $url);

        $this->assertResponseIsSuccessful();
    }

    public function urlAnonymousProvider()
    {
        yield ['/'];
        yield ['/login'];
        yield ['/register'];
    }

    /**
     * On crée une méthode pour tester des routes accessibles en back
     * 
     * @dataProvider urlAdminProvider
     */
    public function testAdminRoutes($url)
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'jupiter',
            'PHP_AUTH_PW'   => '123456',
        ]);
        $crawler = $client->request('GET', $url);

        $this->assertResponseIsSuccessful();
    }

    public function urlAdminProvider()
    {
        yield ['/movie/list'];
        yield ['/job/list'];
        yield ['/department/list'];
        yield ['/casting/list'];
        yield ['/genre/list'];
        yield ['/admin/user/'];
    }
}
