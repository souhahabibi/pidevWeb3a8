<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', null, [
                'attr' => ['placeholder' => 'Enter your name', 'style' => 'width: 300px;'],
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('email', null, [
                'attr' => ['placeholder' => 'Enter your email', 'style' => 'width: 300px;'],
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('motdepasse', PasswordType::class, [
                'attr' => ['placeholder' => 'Enter your password', 'style' => 'width: 300px;'],
                'label_attr' => ['class' => 'form-label'],
                'required' => false,

            ])
            ->add('specialite', ChoiceType::class, [
                'choices' => [
                    'Musculation' => 'Musculation',
                    'CrossFit' => 'CrossFit',
                    'PowerLifting' => 'PowerLifting',
                ],
                'placeholder' => 'Choose a specialization',
                'attr' => ['placeholder' => 'Select your specialization', 'style' => 'width: 300px;'],
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('numero', null, [
                'attr' => ['placeholder' => 'Enter your number', 'style' => 'width: 300px;'],
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('recommandation', ChoiceType::class, [
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'placeholder' => 'Choose an option',
                'attr' => ['placeholder' => 'Select an option', 'style' => 'width: 300px;'],
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('poids', null, [
                'attr' => ['placeholder' => 'Enter your weight', 'style' => 'width: 300px;'],
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('taille', null, [
                'attr' => ['placeholder' => 'Enter your height', 'style' => 'width: 300px;'],
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('niveau', ChoiceType::class, [
                'choices' => [
                    'débutant' => 'débutant',
                    'intémediaire' => 'intémediaire',
                    'professionnel' => 'professionnel',
                ],
                'placeholder' => 'Choose your level',
                'attr' => ['placeholder' => 'Select your level', 'style' => 'width: 300px;'],
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('role', ChoiceType::class, [
                'choices' => [
                    'Admin' => 'admin',
                    'Coach' => 'coach',
                    'Client' => 'client',
                ],
                'placeholder' => 'Choose a role',
                'attr' => ['placeholder' => 'Select your role', 'style' => 'width: 300px;'],
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('mailcode', null, [
                'attr' => ['placeholder' => 'Enter your mail code', 'style' => 'width: 300px;'],
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('isVerified', null, [
                'attr' => ['placeholder' => 'Enter verification status', 'style' => 'width: 300px;'],
                'label_attr' => ['class' => 'form-label'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
