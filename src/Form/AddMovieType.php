<?php

namespace App\Form;

use App\Entity\Movie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class AddMovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $option)
    {
         $builder
            ->add('title', null, [
                'label' => 'Titre',
            ])
             /*->add('color', ColorType::class, [
                 'mapped' => false
             ])*/
             //->add('createdAt')
             //->add('updatedAt')
             //->add('movies') // On cache les movies parce que le owner de larelation est Movie et que on se retrouve à ne pas stocker cette valeur en BDD, du moins pour le moment*
             ->add('send', SubmitType::class, [
                'label' => 'Enregistrer',
            ]);

            /*$builder->add('name', EntityType::class, [
                'class' => Movie::class,
                'choice_label' => 'name'
            ]);*/
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }
}

