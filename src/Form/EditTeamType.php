<?php

namespace App\Form;

use App\Entity\Team;
use App\Entity\Job;
use App\Entity\Person;
use App\Entity\Movie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class EditTeamType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $option)
    {
         $builder
            ->add('movie', EntityType::class, [
                'class' => Movie::class,
                'choice_label' => 'title',
            ])
            ->add('person', EntityType::class, [
                'class' => Person::class,
                'choice_label' => 'name',
            ])
            ->add('job', EntityType::class, [
                'class' => Job::class,
                'choice_label' => 'name',
            ])
            ->add('send', SubmitType::class, [
                'label' => 'Enregistrer',
            ])
             ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Team::class,
        ]);
    }
}