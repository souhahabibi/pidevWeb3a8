<?php

namespace App\Form;

use App\Entity\Ingredients;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class IngredientsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('calories')
            ->add('totalFat')
            ->add('protein')
            ->add('imgurl', FileType::class, [
                'label' => 'Image',
                'required' => false, // La rend facultative
                'mapped' => false,
            ])
            ->add('save',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ingredients::class,
        ]);
    }
}
