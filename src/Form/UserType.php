<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('email')
            ->add('motdepasse')
            ->add('specialite', ChoiceType::class, [
                'choices' => [
                    'Musculation' => 'Musculation',
                    'CrossFit' => 'CrossFit',
                    'PowerLifting' => 'PowerLifting',
                ],
                'placeholder' => 'Choose a specialization',
            ])
            ->add('numero')
            ->add('recommandation', ChoiceType::class, [
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'placeholder' => 'Choose an option',
            ])            ->add('poids')
            ->add('taille')
            ->add('niveau', ChoiceType::class, [
                'choices' => [
                    'débutant' => 'débutant',
                    'intémediaire' => 'intémediaire',
                    'professionnel' => 'professionnel',
                ],
            'placeholder' => 'Choose your level',])
            ->add('role', ChoiceType::class, [
                'choices' => [
                    'Admin' => 'admin',
                    'Coach' => 'coach',
                    'Client' => 'client',
                ],
                'placeholder' => 'Choose a role',
            ])
            ->add('mailcode')
            ->add('isVerified')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
