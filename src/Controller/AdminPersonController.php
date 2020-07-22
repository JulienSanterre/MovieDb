<?php

namespace App\Controller;

use App\Entity\Person;
use App\Form\AddPersonType;
use App\Form\EditPersonType;
use App\Form\DeletePersonType;
use App\Repository\PersonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/person", name="person_")
 */
class AdminPersonController extends AbstractController
{
    /**
     * @Route("/list", name="list")
     */
    public function list(PersonRepository $personRepository)
    {
        // On souhaite afficher la liste de tous les films pour permettre de les modifier
        $persons = $personRepository->findAllByName();

        return $this->render('admin/show_persons_list.html.twig', [
            'persons' => $persons,
        ]);
    }
    /**
     * Affiche un formulaire vide pour créer un nouveau Person dans la BDD
     * 
     * @Route("/add", name="add")
     */
    public function add(Request $request, EntityManagerInterface $em)
    {
        // On crée un objet Person qu'on attribue au formulaire
        $person = new Person();
        $form = $this->createForm(AddPersonType::class, $person);

        // Quand le formulaire utilise les données reçues en POST dans $request, il met à jour $person
        $form->handleRequest($request);

        // Une fois que le formulaire a observé la requête,
        // il a éventuellement associé les données en POST avec le formulaire
        // En plus, il a prérempli, l'objet $person

        // On teste maintenant si les données sont bien reçues
        if ($form->isSubmitted() && $form->isValid()) {
            // Le formulaire est bien envoyé et les valeurs reçues sont valides
            // On peut donc persister notre $person
            $this->addFlash(
                'success',
                'L\'interprète a bien été ajouté.'
            );

            $em->persist($person);
            $em->flush();
        }

        return $this->render('person/person_add.html.twig', [
            // On envoie toujours à Twig un objet FormView et pas juste un objet Form
            'formPerson' => $form->createView()
        ]);
    }

    /**
     * On peut modifier un person avec la form FormType
     * 
     * @Route("/edit/{apiId}", name="edit")
     */
    public function edit(Request $request, EntityManagerInterface $em, Person $person)
    {
        // On a déjà un person existant de la base de données

        // Tout ocmme pour add(), on créer un formulaire à partir de PersonType
        $form = $this->createForm(EditPersonType::class, $person);
        // On obtient un objet $form dont les champs sont préremplis avec les données de $person

        // hendlaRequest associe les données de la requête avec le formulaire
        $form->handleRequest($request);

        // On teste maintenant si les données sont bien reçues
        if ($form->isSubmitted() && $form->isValid()) {
            // Le formulaire est bien envoyé et les valeurs reçues sont valides
            // On peut donc persister notre $person
            // $em->persist($person);
            $person->setUpdatedAt(new \DateTime('now'));
            $this->addFlash(
                'warning',
                'L\'interprète a bien été édité.'
            );

            $em->flush();
        }

        return $this->render('person/person_add.html.twig', [
            'formPerson' => $form->createView()
        ]);
    }
    
    /**
     * On peut modifier un person avec la form FormType
     * 
     * @Route("/delete", name="delete")
     */
    public function delete(Request $request, EntityManagerInterface $em, PersonRepository $personRepository)
    {
        // On a déjà un person existant de la base de données

        // Tout ocmme pour add(), on créer un formulaire à partir de PersonType
        $form = $this->createForm(DeletePersonType::class);
        // On obtient un objet $form dont les champs sont préremplis avec les données de $Person

        // hendlaRequest associe les données de la requête avec le formulaire
        $form->handleRequest($request);

        // On teste maintenant si les données sont bien reçues
        if ($form->isSubmitted() && $form->isValid()) {
            $person = $personRepository->find($request->request->get('delete_person')['name']);
            //dd($form->createView());
            // Le formulaire est bien envoyé et les valeurs reçues sont valides
            // On peut donc supprimer notre $person
            $this->addFlash(
                'danger',
                'L\'interprète a bien été supprimé.'
            );

            $em->remove($person);
            $em->flush();
        }

        return $this->render('person/inperson_add.html.twig', [
            'formPerson' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{apiId}", name="deleteId")
     */
    public function deleteId(Person $person, EntityManagerInterface $em)
    {

        // Après le flush on met le flash
        $this->addFlash(
            'danger',
            'L\'interprète a bien été supprimé.'
        );

        // On supprime le person, pour le moment sans confirmation !
        $em->remove($person);
        $em->flush();

        return $this->redirectToRoute('person_list');
    }
}
