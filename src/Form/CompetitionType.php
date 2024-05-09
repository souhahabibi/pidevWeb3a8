<?php

namespace App\Form;

use App\Entity\Competition;
use App\Entity\Organisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CompetitionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom', TextType::class, ['attr' => ['class' => 'form-control']])
            ->add('date', DateType::class, ['attr' => ['class' => 'form-control']])
            ->add('description', TextareaType::class, ['attr' => ['class' => 'form-control']])
            ->add('capacite', IntegerType::class, ['attr' => ['class' => 'form-control']])
            ->add('videourl', TextType::class, ['attr' => ['class' => 'form-control']])
            ->add('fkOrganisateur',EntityType::class,[
                'class'=>Organisateur::class,
                'choice_label'=>'nom',
                'required'=>true,
                'expanded'=>false,
                'multiple'=>false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('Submit', SubmitType::class, ['attr' => ['class' => 'btn btn-success m-2']]);
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Competition::class,
        ]);
    }
}
