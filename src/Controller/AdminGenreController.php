<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Form\AddGenreType;
use App\Form\EditGenreType;
use App\Form\DeleteGenreType;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

use App\Manager\ApiDataGenreManager;

/**
 * @Route("/genre", name="genre_")
 */
class AdminGenreController extends AbstractController
{
    private $apiDataGenreManager;
    private $em;
    private $flashBag;

    public function __construct(ApiDataGenreManager $apiDataGenreManager, EntityManagerInterface $em, FlashBagInterface $flashBag)
    {
        $this->apiDataGenreManager = $apiDataGenreManager;
        $this->em = $em;
        $this->flashBag = $flashBag;
    }

    /**
     * @Route("/list", name="list")
     */
    public function list(GenreRepository $GenreRepository)
    {
        // On souhaite afficher la liste de tous les films pour permettre de les modifier
        $genres = $GenreRepository->findAllByName();

        return $this->render('admin/show_genres_list.html.twig', [
            'genres' => $genres,
        ]);
    }

    /**
     * Affiche un formulaire vide pour créer un nouveau Genre dans la BDD
     * 
     * @Route("/add", name="add")
     */
    public function add(GenreRepository $GenreRepository)
    {
        $this->apiDataGenreManager->setGenreDataApi();

        return $this->redirectToRoute('genre_list', [
            'genres' => $GenreRepository->findAllByName(),
        ]);
    }

    /**
     * On peut modifier un Genre avec la form FormType
     * 
     * @Route("/edit/{apiId}", name="edit")
     */
    public function edit(Request $request, Genre $genre)
    {
        // On a déjà un Genre existant de la base de données

        // Tout ocmme pour add(), on créer un formulaire à partir de GenreType
        $form = $this->createForm(EditGenreType::class, $genre);
        // On obtient un objet $form dont les champs sont préremplis avec les données de $genre

        // hendlaRequest associe les données de la requête avec le formulaire
        $form->handleRequest($request);

        // On teste maintenant si les données sont bien reçues
        if ($form->isSubmitted() && $form->isValid()) {
            // Le formulaire est bien envoyé et les valeurs reçues sont valides
            // On peut donc persister notre $genre
            // $em->persist($genre);
            $genre->setUpdatedAt(new \DateTime('now'));
            $this->addFlash(
                'warning',
                'Le genre a bien été édité.'
            );

            $this->em->flush();
        }

        return $this->render('genre/genre_add.html.twig', [
            'formGenre' => $form->createView()
        ]);
    }
    
    /**
     * On peut modifier un Genre avec la form FormType
     * 
     * @Route("/delete", name="delete")
     */
    public function delete(Request $request, GenreRepository $genreRepository)
    {
        // On a déjà un Genre existant de la base de données

        // Tout ocmme pour add(), on créer un formulaire à partir de GenreType
        $form = $this->createForm(DeleteGenreType::class);
        // On obtient un objet $form dont les champs sont préremplis avec les données de $genre

        // hendlaRequest associe les données de la requête avec le formulaire
        $form->handleRequest($request);

        // On teste maintenant si les données sont bien reçues
        if ($form->isSubmitted() && $form->isValid()) {
            $genre = $genreRepository->find($request->request->get('delete_genre')['name']);
            //dd($form->createView());
            // Le formulaire est bien envoyé et les valeurs reçues sont valides
            // On peut donc supprimer notre $genre
            $this->addFlash(
                'danger',
                'Le genre a bien été supprimé.'
            );

            $this->em->remove($genre);
            $this->em->flush();
        }

        return $this->render('genre/genre_add.html.twig', [
            'formGenre' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{apiId}", name="deleteId")
     */
    public function deleteId(Genre $genre)
    {

        // Après le flush on met le flash
        $this->addFlash(
            'danger',
            'Le genre a bien été supprimé.'
        );

        // On supprime le movie, pour le moment sans confirmation !
        $this->em->remove($genre);
        $this->em->flush();

        return $this->redirectToRoute('genre_list');
    }
}
