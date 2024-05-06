<?php

namespace App\Form;

use App\Entity\Coupon;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CouponType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomsociete')
            ->add('code')
            ->add('valeur')
            ->add('dateexpiration')
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'nom', // Choisissez le champ à afficher pour l'utilisateur
                'placeholder' => 'Sélectionnez un utilisateur', // Optionnel : texte à afficher comme choix vide
                'required' => false, // Optionnel : définissez à true si la sélection d'un utilisateur est requise
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Coupon::class,
        ]);
    }
}
