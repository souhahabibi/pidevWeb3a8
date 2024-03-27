<?php

namespace App\Form;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Cours;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CoursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('image', FileType::class, [
            'label' => 'Image du cours',
            'required' => false, // La rend facultative
            'mapped' => false,
        ])
        ->add('nom')
        ->add('description')
        ->add('niveau', ChoiceType::class, [
            'choices'  => [
                'Débutant' => 'Débutant',
                'Normal' => 'Normal',
                'Avancé' => 'Avancé',
            ],
            'expanded' => true,
        ])
        ->add('user', EntityType::class, [
            'class' => 'App\Entity\User',
            'choice_label' => 'nom', 
        ]);
    }    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cours::class,
        ]);
    }
}
