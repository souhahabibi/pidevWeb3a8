<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Competition;
use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('score', IntegerType::class, ['attr' => ['class' => 'form-control']])
            ->add('fkCompetition',EntityType::class,[
                'class'=>Competition::class,
                'choice_label'=>'nom',
                'required'=>true,
                'expanded'=>false,
                'disabled' => true, 
                'multiple'=>false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('fkClient',EntityType::class,[
                'class'=>User::class,
                'choice_label'=>'nom',
                'required'=>true,
                'expanded'=>false,
                'disabled' => true, 
                'multiple'=>false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('Submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-success m-2']
            ]);
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
