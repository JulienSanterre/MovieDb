<?php

namespace App\Form;

use App\Entity\Person;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class EditPersonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $option)
    {
         $builder
             ->add('name', TextType::class, [
                'attr' => ['placeholder' => $option['data']->getName()]
             ])
             ->add('send', SubmitType::class, [
                'label' => 'Enregistrer',
            ])
             ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Person::class,
        ]);
    }
}

