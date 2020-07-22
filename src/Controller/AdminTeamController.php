<?php

namespace App\Controller;

use App\Entity\Team;
use App\Form\AddTeamType;
use App\Form\EditTeamType;
use App\Form\DeleteTeamType;
use App\Repository\TeamRepository;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/team", name="team_")
 */
class AdminTeamController extends AbstractController
{
    /**
     * Affiche un formulaire vide pour créer un nouveau team dans la BDD
     * 
     * @Route("/add", name="add")
     */
    public function add(Request $request, EntityManagerInterface $em)
    {
        // On crée un objet Team qu'on attribue au formulaire
        $team = new Team();
        $form = $this->createForm(AddTeamType::class, $team);

        // Quand le formulaire utilise les données reçues en POST dans $request, il met à jour $team
        $form->handleRequest($request);

        // Une fois que le formulaire a observé la requête,
        // il a éventuellement associé les données en POST avec le formulaire
        // En plus, il a prérempli, l'objet $team

        // On teste maintenant si les données sont bien reçues
        if ($form->isSubmitted() && $form->isValid()) {
            // Le formulaire est bien envoyé et les valeurs reçues sont valides
            // On peut donc persister notre $team
            $this->addFlash(
                'success',
                'Le Team a bien été ajouté.'
            );

            $em->persist($team);
            $em->flush();
        }

        return $this->render('team/team_add.html.twig', [
            // On envoie toujours à Twig un objet FormView et pas juste un objet Form
            'formTeam' => $form->createView()
        ]);
    }

    /**
     * On peut modifier un team avec la form FormType
     * 
     * @Route("/edit/{id}", name="edit")
     */
    public function edit(Request $request, EntityManagerInterface $em, Team $team)
    {
        // On a déjà un team existant de la base de données

        // Tout ocmme pour add(), on créer un formulaire à partir de TeamType
        $form = $this->createForm(EditTeamType::class, $team);
        // On obtient un objet $form dont les champs sont préremplis avec les données de $team

        // hendlaRequest associe les données de la requête avec le formulaire
        $form->handleRequest($request);

        // On teste maintenant si les données sont bien reçues
        if ($form->isSubmitted() && $form->isValid()) {
            // Le formulaire est bien envoyé et les valeurs reçues sont valides
            // On peut donc persister notre $team
            // $em->persist($team);
            $this->addFlash(
                'warning',
                'Le team a bien été édité.'
            );

            $em->flush();
        }

        return $this->render('team/team_add.html.twig', [
            'formTeam' => $form->createView()
        ]);
    }
    
    /**
     * On peut modifier un team avec la form FormType
     * 
     * @Route("/delete", name="delete")
     */
    public function delete(Request $request, EntityManagerInterface $em, TeamRepository $teamRepository, MovieRepository $movieRepository)
    {
        // On a déjà un Team existant de la base de données

        // Tout ocmme pour add(), on créer un formulaire à partir de TeamType
        $form = $this->createForm(DeleteTeamType::class);
        // On obtient un objet $form dont les champs sont préremplis avec les données de $team

        // hendlaRequest associe les données de la requête avec le formulaire
        $form->handleRequest($request);

        // On teste maintenant si les données sont bien reçues
        if ($form->isSubmitted() && $form->isValid()) {

            $movie = $movieRepository->find($request->request->get('delete_team')['movie']);
            $teams = $teamRepository->findBy(array ('movie' => $movie));
            
            
            // Le formulaire est bien envoyé et les valeurs reçues sont valides
            // On peut donc supprimer notre $team
            $this->addFlash(
                'danger',
                'Le team a bien été supprimé.'
            );

            foreach ($teams as $team){
                $em->remove($team);
                $em->flush();
            }
        }

        return $this->render('team/team_add.html.twig', [
            'formTeam' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="deleteId")
     */
    public function deleteId(Team $team, EntityManagerInterface $em)
    {

        // Après le flush on met le flash
        $this->addFlash(
            'danger',
            'Le team a bien été supprimé.'
        );

        // On supprime le team, pour le moment sans confirmation !
        $em->remove($team);
        $em->flush();

        return $this->redirectToRoute('team_list');
    }
}
