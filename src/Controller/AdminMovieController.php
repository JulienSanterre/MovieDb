<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\AddMovieType;
use App\Form\EditMovieType;
use App\Form\DeleteMovieType;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

use App\Manager\ApiDataMovieManager;
use App\Repository\CastingRepository;
use App\Repository\TeamRepository;
use App\Service\Slugger;

/**
 * @Route("/movie", name="movie_")
 */
class AdminMovieController extends AbstractController
{
    private $apiDataMovieManager;
    private $em;
    private $flashBag;
    private $movie;
    private $slugger;
    private $teamRepository;
    private $castingRepository;

    public function __construct(Slugger $slugger, ApiDataMovieManager $apiDataMovieManager, EntityManagerInterface $em, FlashBagInterface $flashBag
    ,TeamRepository $teamRepository, CastingRepository $castingRepository)
    {
        $this->apiDataMovieManager = $apiDataMovieManager;
        $this->em = $em;
        $this->slugger = $slugger;
        $this->flashBag = $flashBag;
        $this->teamRepository = $teamRepository;
        $this->castingRepository = $castingRepository;
    }

    /**
     * @Route("/list", name="list")
     */
    public function list(MovieRepository $movieRepository)
    {
        // On souhaite afficher la liste de tous les films pour permettre de les modifier
        $movies = $movieRepository->findAllByTitle();

        return $this->render('admin/show_movies_list.html.twig', [
            'movies' => $movies,
        ]);
    }

    /**
     * Affiche un formulaire vide pour créer un nouveau movie dans la BDD
     * 
     * @Route("/add", name="add")
     */
    public function add(Request $request)
    {
        // On crée un objet movie qu'on attribue au formulaire
        $movie = new Movie();
        $form = $this->createForm(AddMovieType::class, $movie);

        // Quand le formulaire utilise les données reçues en POST dans $request, il met à jour $movie
        $form->handleRequest($request);

        // Une fois que le formulaire a observé la requête,
        // il a éventuellement associé les données en POST avec le formulaire
        // En plus, il a prérempli, l'objet $movie

        // On teste maintenant si les données sont bien reçues
        if ($form->isSubmitted() && $form->isValid()) {
            
            $this->movie = $this->apiDataMovieManager->setMovieDataApi($movie);

            if(isset($this->movie) && $this->movie->getId() != NULL){
                return $this->redirectToRoute('movie_show', ['id' => $this->movie->getId(),'movie' => $this->movie]); 
            }elseif($this->flashBag->peekAll() == NULL){
                $this->flashBag->add('warning', 'Aucun film portant ce nom n\'a été trouvé');
            } 
        }

        return $this->render('movie/movie_add.html.twig', [
            // On envoie toujours à Twig un objet FormView et pas juste un objet Form
            'formMovie' => $form->createView()
        ]);
    }

    /**
     * On peut modifier un movie avec la form FormType
     * 
     * @Route("/edit/{id}", name="edit")
     */
    public function edit(Request $request, Movie $movie)
    {
        // On a déjà un movie existant de la base de données

        // Tout ocmme pour add(), on créer un formulaire à partir de movieType
        $form = $this->createForm(EditMovieType::class, $movie);
        // On obtient un objet $form dont les champs sont préremplis avec les données de $movie

        // hendlaRequest associe les données de la requête avec le formulaire
        $form->handleRequest($request);

        // On teste maintenant si les données sont bien reçues
        if ($form->isSubmitted() && $form->isValid()) {
            // Le formulaire est bien envoyé et les valeurs reçues sont valides
            // On peut donc persister notre $movie
            // $em->persist($movie);
            // On précise le slug du Movie
            

            $movie->setSlug($this->slugger->slugify($movie->getTitle()));
            $movie->setUpdatedAt(new \DateTime('now'));
            $this->em->flush();
            $this->addFlash(
                'warning',
                'Le movie a bien été édité.'
            );

            $this->addFlash('success', 'Le film "' . $movie->getTitle() . '" a bien été modifié');
        }

        return $this->render('movie/movie_add.html.twig', [
            'formMovie' => $form->createView()
        ]);
    }
    
    /**
     * On peut modifier un movie avec la form FormType
     * 
     * @Route("/delete", name="delete")
     */
    public function delete(Request $request, MovieRepository $movieRepository)
    {
        // On a déjà un movie existant de la base de données

        // Tout ocmme pour add(), on créer un formulaire à partir de MovieType
        $form = $this->createForm(DeleteMovieType::class);
        // On obtient un objet $form dont les champs sont préremplis avec les données de $Movie

        // hendlaRequest associe les données de la requête avec le formulaire
        $form->handleRequest($request);

        // On teste maintenant si les données sont bien reçues
        if ($form->isSubmitted() && $form->isValid()) {
            
            $movie = $movieRepository->find($request->request->get('delete_movie')['title']);
            
            // Le formulaire est bien envoyé et les valeurs reçues sont valides
            // On peut donc supprimer notre $Movie
            $this->addFlash(
                'danger',
                'Le movie a bien été supprimé.'
            );

            $this->em->remove($movie);
            $this->em->flush();
        }

        return $this->render('movie/movie_add.html.twig', [
            'formMovie' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="deleteId")
     */
    public function deleteId(Movie $movie)
    {

        // Après le flush on met le flash
        $this->addFlash(
            'danger',
            'Le movie a bien été supprimé.'
        );

        // On supprime le movie, pour le moment sans confirmation !
        $this->em->remove($movie);
        $this->em->flush();

        return $this->redirectToRoute('movie_list');
    }
}
