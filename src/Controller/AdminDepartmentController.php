<?php

namespace App\Controller;

use App\Entity\Department;
use App\Form\AddDepartmentType;
use App\Form\EditDepartmentType;
use App\Form\DeleteDepartmentType;
use App\Repository\DepartmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/department", name="department_")
 */
class AdminDepartmentController extends AbstractController
{
    /**
     * @Route("/list", name="list")
     */
    public function list(DepartmentRepository $departmentRepository)
    {
        // On souhaite afficher la liste de tous les films pour permettre de les modifier
        $departments = $departmentRepository->findAllByName();

        return $this->render('admin/show_departments_list.html.twig', [
            'departments' => $departments,
        ]);
    }

    /**
     * Affiche un formulaire vide pour créer un nouveau department dans la BDD
     * 
     * @Route("/add", name="add")
     */
    public function add(Request $request, EntityManagerInterface $em)
    {
        // On crée un objet department qu'on attribue au formulaire
        $department = new Department();
        $form = $this->createForm(AddDepartmentType::class, $department);

        // Quand le formulaire utilise les données reçues en POST dans $request, il met à jour $department
        $form->handleRequest($request);

        // Une fois que le formulaire a observé la requête,
        // il a éventuellement associé les données en POST avec le formulaire
        // En plus, il a prérempli, l'objet $department

        // On teste maintenant si les données sont bien reçues
        if ($form->isSubmitted() && $form->isValid()) {
            // Le formulaire est bien envoyé et les valeurs reçues sont valides
            // On peut donc persister notre $department
            $this->addFlash(
                'success',
                'Le department a bien été ajouté.'
            );

            $em->persist($department);
            $em->flush();
        }

        return $this->render('department/department_add.html.twig', [
            // On envoie toujours à Twig un objet FormView et pas juste un objet Form
            'formDepartment' => $form->createView()
        ]);
    }

    /**
     * On peut modifier un department avec la form FormType
     * 
     * @Route("/edit/{id}", name="edit")
     */
    public function edit(Request $request, EntityManagerInterface $em, Department $department)
    {
        // On a déjà un department existant de la base de données

        // Tout ocmme pour add(), on créer un formulaire à partir de departmentType
        $form = $this->createForm(EditDepartmentType::class, $department);
        // On obtient un objet $form dont les champs sont préremplis avec les données de $department

        // hendlaRequest associe les données de la requête avec le formulaire
        $form->handleRequest($request);

        // On teste maintenant si les données sont bien reçues
        if ($form->isSubmitted() && $form->isValid()) {
            // Le formulaire est bien envoyé et les valeurs reçues sont valides
            // On peut donc persister notre $department
            // $em->persist($department);
            $department->setUpdatedAt(new \DateTime('now'));
            $this->addFlash(
                'warning',
                'Le department a bien été édité.'
            );

            $em->flush();
        }

        return $this->render('department/department_add.html.twig', [
            'formDepartment' => $form->createView()
        ]);
    }
    
    /**
     * On peut modifier un department avec la form FormType
     * 
     * @Route("/delete", name="delete")
     */
    public function delete(Request $request, EntityManagerInterface $em, DepartmentRepository $departmentRepository)
    {
        // On a déjà un department existant de la base de données

        // Tout ocmme pour add(), on créer un formulaire à partir de departmentType
        $form = $this->createForm(DeleteDepartmentType::class);
        // On obtient un objet $form dont les champs sont préremplis avec les données de $department

        // hendlaRequest associe les données de la requête avec le formulaire
        $form->handleRequest($request);

        // On teste maintenant si les données sont bien reçues
        if ($form->isSubmitted() && $form->isValid()) {
            $department = $departmentRepository->find($request->request->get('delete_department')['name']);
            //dd($form->createView());
            // Le formulaire est bien envoyé et les valeurs reçues sont valides
            // On peut donc supprimer notre $department
            $this->addFlash(
                'danger',
                'Le department a bien été supprimé.'
            );

            $em->remove($department);
            $em->flush();
        }

        return $this->render('department/department_add.html.twig', [
            'formDepartment' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="deleteId")
     */
    public function deleteId(Department $department, EntityManagerInterface $em)
    {

        // Après le flush on met le flash
        $this->addFlash(
            'danger',
            'Le department a bien été supprimé.'
        );

        // On supprime le department, pour le moment sans confirmation !
        $em->remove($department);
        $em->flush();

        return $this->redirectToRoute('department_list');
    }
}
