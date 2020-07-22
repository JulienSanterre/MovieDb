<?php

namespace App\Controller;

use App\Entity\Job;
use App\Form\AddJobType;
use App\Form\EditJobType;
use App\Form\DeleteJobType;
use App\Repository\JobRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/job", name="job_")
 */
class AdminJobController extends AbstractController
{
    /**
     * @Route("/list", name="list")
     */
    public function list(JobRepository $jobRepository)
    {
        // On souhaite afficher la liste de tous les films pour permettre de les modifier
        $jobs = $jobRepository->findAllByName();

        return $this->render('admin/show_jobs_list.html.twig', [
            'jobs' => $jobs,
        ]);
    }

    /**
     * Affiche un formulaire vide pour créer un nouveau job dans la BDD
     * 
     * @Route("/add", name="add")
     */
    public function add(Request $request, EntityManagerInterface $em)
    {
        // On crée un objet job qu'on attribue au formulaire
        $job = new Job();
        $form = $this->createForm(AddJobType::class, $job);

        // Quand le formulaire utilise les données reçues en POST dans $request, il met à jour $Job
        $form->handleRequest($request);

        // Une fois que le formulaire a observé la requête,
        // il a éventuellement associé les données en POST avec le formulaire
        // En plus, il a prérempli, l'objet $Job

        // On teste maintenant si les données sont bien reçues
        if ($form->isSubmitted() && $form->isValid()) {
            // Le formulaire est bien envoyé et les valeurs reçues sont valides
            // On peut donc persister notre $Job
            $this->addFlash(
                'success',
                'Le rôle a bien été ajouté.'
            );

            $em->persist($job);
            $em->flush();
        }

        return $this->render('job/job_add.html.twig', [
            // On envoie toujours à Twig un objet FormView et pas juste un objet Form
            'formJob' => $form->createView()
        ]);
    }

    /**
     * On peut modifier un Job avec la form FormType
     * 
     * @Route("/edit/{id}", name="edit")
     */
    public function edit(Request $request, EntityManagerInterface $em, Job $job)
    {
        // On a déjà un Job existant de la base de données

        // Tout ocmme pour add(), on créer un formulaire à partir de JobType
        $form = $this->createForm(EditJobType::class, $job);
        // On obtient un objet $form dont les champs sont préremplis avec les données de $Job

        // hendlaRequest associe les données de la requête avec le formulaire
        $form->handleRequest($request);

        // On teste maintenant si les données sont bien reçues
        if ($form->isSubmitted() && $form->isValid()) {
            // Le formulaire est bien envoyé et les valeurs reçues sont valides
            // On peut donc persister notre $job
            // $em->persist($job);
            $job->setUpdatedAt(new \DateTime('now'));
            $this->addFlash(
                'warning',
                'Le rôle a bien été édité.'
            );

            $em->flush();
        }

        return $this->render('job/job_add.html.twig', [
            'formJob' => $form->createView()
        ]);
    }
    
    /**
     * On peut modifier un Job avec la form FormType
     * 
     * @Route("/delete", name="delete")
     */
    public function delete(Request $request, EntityManagerInterface $em, JobRepository $jobRepository)
    {
        // On a déjà un Job existant de la base de données

        // Tout ocmme pour add(), on créer un formulaire à partir de JobType
        $form = $this->createForm(DeleteJobType::class);
        // On obtient un objet $form dont les champs sont préremplis avec les données de $Job

        // hendlaRequest associe les données de la requête avec le formulaire
        $form->handleRequest($request);

        // On teste maintenant si les données sont bien reçues
        if ($form->isSubmitted() && $form->isValid()) {
            
            $job = $jobRepository->find($request->request->get('delete_job')['name']);
            
            // Le formulaire est bien envoyé et les valeurs reçues sont valides
            // On peut donc supprimer notre $job
            $this->addFlash(
                'danger',
                'Le rôle a bien été supprimé.'
            );

            $em->remove($job);
            $em->flush();
        }

        return $this->render('job/job_add.html.twig', [
            'formJob' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="deleteId")
     */
    public function deleteId(Job $job, EntityManagerInterface $em)
    {

        // Après le flush on met le flash
        $this->addFlash(
            'danger',
            'Le rôle a bien été supprimé.'
        );

        // On supprime le movie, pour le moment sans confirmation !
        $em->remove($job);
        $em->flush();

        return $this->redirectToRoute('job_list');
    }
}
