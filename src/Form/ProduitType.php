<?php

namespace App\Form;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('quantite')
            ->add('cout')
            ->add('dateExpiration')
            ->add('description')
            ->add('image', FileType::class, [
                'label' => 'Image du produit',
                'required' => false, // La rend facultative
                'mapped' => false,
            ])
            ->add('idFournisseur')
         
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
