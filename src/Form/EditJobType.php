<?php

namespace App\Form;

use App\Entity\Job;
use App\Entity\Department;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class EditJobType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $option)
    {
         $builder
             ->add('name', TextType::class, [
                'attr' => ['placeholder' => $option['data']->getName()]
             ])
             ->add('department', EntityType::class, [
                'class' => Department::class,
                'choice_label' => 'name'
             ])
             /*->add('color', ColorType::class, [
                 'mapped' => false
             ])*/
             //->add('createdAt')
             //->add('updatedAt')
             //->add('movies') // On cache les movies parce que le owner de larelation est Movie et que on se retrouve à ne pas stocker cette valeur en BDD, du moins pour le moment*
             ->add('send', SubmitType::class, [
                'label' => 'Enregistrer',
            ])
             ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Job::class,
        ]);
    }
}