<?php

namespace App\Form;

use App\Entity\Casting;
use App\Entity\Movie;
use App\Entity\Person;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class EditCastingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $option)
    {
         $builder
             ->add('movie', EntityType::class, [
                'attr' => ['placeholder' => $option['data']->getMovie()->getTitle()],
                 'class' => Movie::class,
                 'choice_label' => 'title'
             ])
             ->add('person', EntityType::class, [
                'attr' => ['placeholder' => $option['data']->getPerson()->getName()],
                'class' => Person::class,
                'choice_label' => 'name'
             ])
             ->add('role', null,[
                'attr' => ['placeholder' => $option['data']->getRole()]
             ])
             ->add('credit_order', IntegerType::class,[
                'attr' => ['placeholder' => $option['data']->getCreditOrder()]
             ])
             ->add('send', SubmitType::class, [
                'label' => 'Enregistrer',
            ]); 
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Casting::class,
        ]);
    }
}

