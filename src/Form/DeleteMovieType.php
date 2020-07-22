<?php

namespace App\Form;

use App\Entity\Movie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class DeleteMovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $option)
    {
            $builder->add('title', EntityType::class, [
                'class' => Movie::class,
                'choice_label' => 'title'
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

