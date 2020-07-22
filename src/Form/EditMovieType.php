<?php

namespace App\Form;

use App\Entity\Movie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class EditMovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $option)
    {
         $builder
             ->add('title', TextType::class, [
                'attr' => ['placeholder' => $option['data']->getTitle()]
             ])
            ->add('send', SubmitType::class, [
                'label' => 'Enregistrer',
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }
}

