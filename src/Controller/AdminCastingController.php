<?php

namespace App\Controller;

use App\Entity\Casting;
use App\Form\AddCastingType;
use App\Form\EditCastingType;
use App\Form\DeleteCastingType;
use App\Repository\CastingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/casting", name="casting_")
 */
class AdminCastingController extends AbstractController
{
    /**
     * @Route("/list", name="list")
     */
    public function list(CastingRepository $castingRepository)
    {
        // On souhaite afficher la liste de tous les films pour permettre de les modifier
        $castings = $castingRepository->findAllByName();

        return $this->render('admin/show_castings_list.html.twig', [
            'castings' => $castings,
        ]);
    }

    /**
     * Affiche un formulaire vide pour créer un nouveau Casting dans la BDD
     * 
     * @Route("/add", name="add")
     */
    public function add(Request $request, EntityManagerInterface $em)
    {
        // On crée un objet Casting qu'on attribue au formulaire
        $casting = new Casting();
        $form = $this->createForm(AddCastingType::class, $casting);

        // Quand le formulaire utilise les données reçues en POST dans $request, il met à jour $Casting
        $form->handleRequest($request);

        // Une fois que le formulaire a observé la requête,
        // il a éventuellement associé les données en POST avec le formulaire
        // En plus, il a prérempli, l'objet $Casting

        // On teste maintenant si les données sont bien reçues
        if ($form->isSubmitted() && $form->isValid()) {
            // Le formulaire est bien envoyé et les valeurs reçues sont valides
            // On peut donc persister notre $Casting
            $this->addFlash(
                'success',
                'Le Casting a bien été ajouté.'
            );

            $em->persist($casting);
            $em->flush();
        }

        return $this->render('casting/casting_add.html.twig', [
            // On envoie toujours à Twig un objet FormView et pas juste un objet Form
            'formCasting' => $form->createView()
        ]);
    }

    /**
     * On peut modifier un Casting avec la form FormType
     * 
     * @Route("/edit/{id}", name="edit")
     */
    public function edit(Request $request, EntityManagerInterface $em, Casting $casting)
    {
        // On a déjà un Casting existant de la base de données

        // Tout ocmme pour add(), on créer un formulaire à partir de CastingType
        $form = $this->createForm(EditCastingType::class, $casting);
        // On obtient un objet $form dont les champs sont préremplis avec les données de $Casting

        // hendlaRequest associe les données de la requête avec le formulaire
        $form->handleRequest($request);

        // On teste maintenant si les données sont bien reçues
        if ($form->isSubmitted() && $form->isValid()) {
            // Le formulaire est bien envoyé et les valeurs reçues sont valides
            // On peut donc persister notre $Casting
            // $em->persist($Casting);
            $this->addFlash(
                'warning',
                'Le Casting a bien été édité.'
            );

            $em->flush();
        }

        return $this->render('casting/casting_add.html.twig', [
            'formCasting' => $form->createView()
        ]);
    }
    
    /**
     * On peut modifier un Casting avec la form FormType
     * 
     * @Route("/delete", name="delete")
     */
    public function delete(Request $request, EntityManagerInterface $em, CastingRepository $castingRepository)
    {
        // On a déjà un Casting existant de la base de données

        // Tout ocmme pour add(), on créer un formulaire à partir de CastingType
        $form = $this->createForm(DeleteCastingType::class);
        // On obtient un objet $form dont les champs sont préremplis avec les données de $Casting

        // hendlaRequest associe les données de la requête avec le formulaire
        $form->handleRequest($request);

        // On teste maintenant si les données sont bien reçues
        if ($form->isSubmitted() && $form->isValid()) {
            $casting = $castingRepository->find($request->request->get('delete_casting')['name']);
            //dd($form->createView());
            // Le formulaire est bien envoyé et les valeurs reçues sont valides
            // On peut donc supprimer notre $Casting
            $this->addFlash(
                'danger',
                'Le Casting a bien été supprimé.'
            );

            $em->remove($casting);
            $em->flush();
        }

        return $this->render('casting/casting_add.html.twig', [
            'formCasting' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="deleteId")
     */
    public function deleteId(Casting $casting, EntityManagerInterface $em)
    {

        // Après le flush on met le flash
        $this->addFlash(
            'danger',
            'Le Casting a bien été supprimé.'
        );

        // On supprime le Casting, pour le moment sans confirmation !
        $em->remove($casting);
        $em->flush();

        return $this->redirectToRoute('casting_list');
    }
}
