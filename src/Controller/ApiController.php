<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @Route("/api/v1", name="api_")
 */
class ApiController extends AbstractController
{
    /**
     * @Route("/movies", name="movies_list")
     */
    public function moviesList(MovieRepository $movieRepository)
    {
        // On souhaite retourner un objet avec une structure particulière donnée dans la doc (README.md)
        // On récupére d'abord la liste de tous les films
        $movies = $movieRepository->findAll();

        $arrayMovies = [];

        foreach ($movies as $movie) {
            $arrayMovies[] = [
                'id' => $movie->getId(),
                'title' => $movie->getTitle(),
                'url' => $this->generateUrl('api_movies_single', [
                    'id' => $movie->getId()
                ], UrlGeneratorInterface::ABSOLUTE_URL)
            ];
        }
        // Ce qu'on a fait ici revient à normaliser les données mais on l'a fait à la main 

        // On retourne ensuite ce tableau associatif à la méthode json() qui s'occupe de le sérialiser
        return $this->json($arrayMovies);
    }

    /**
     * @Route("/movies/{id}", name="movies_single")
     */
    public function moviesSingle(Movie $movie)
    {
        return $this->json($movie, 200, [], ['groups' => 'movie_single']);
    }
}
