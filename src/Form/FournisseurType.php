<?php

namespace App\Form;

use App\Entity\Fournisseur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\Regex;
class FournisseurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom', TextType::class, [
            'constraints' => [
                new Regex([
                    'pattern' => '/^\pL+$/u',
                    'message' => 'Le nom ne doit contenir que des lettres.'
                ]),
                new NotBlank([
                    'message' => 'Le nom ne peut pas être vide.'
                ]),
            ]
        ])
        ->add('prenom', TextType::class, [
            'constraints' => [
                new Regex([
                    'pattern' => '/^\pL+$/u',
                    'message' => 'Le prénom ne doit contenir que des lettres.'
                ]),
                new NotBlank([
                    'message' => 'Le prénom ne peut pas être vide.'
                ]),
            ]
        ])
        ->add('numero', IntegerType::class, [
            'constraints' => [
                new Length([
                    'min' => 8,
                    'max' => 8,
                    'exactMessage' => 'Le numéro doit contenir exactement {{ limit }} chiffres.'
                ]),
                new NotBlank([
                    'message' => 'Le numéro ne peut pas être vide.'
                ]),
            ]
        ])
        ->add('type', ChoiceType::class, [
            'choices' => [
                'Vêtements' => 'Vêtements',
                'Compléments Alimentaires' => 'Compléments Alimentaires',
            ],
            'placeholder' => 'Type',
            'constraints' => [
                new NotBlank([
                    'message' => 'Le type ne peut pas être vide.'
                ]),
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Fournisseur::class,
        ]);
    }
}
