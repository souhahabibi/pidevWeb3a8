<?php

namespace App\Form;

use App\Entity\Materiel;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
class MaterielType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, ['attr' => ['class' => 'form-control']])
            ->add('age', IntegerType::class, ['attr' => ['class' => 'form-control']])
            ->add('quantite', IntegerType::class, ['attr' => ['class' => 'form-control']])
            ->add('prix', IntegerType::class, ['attr' => ['class' => 'form-control']])
            ->add('image', FileType::class, [ // Change this to FileType
                'label' => 'Image',
                'mapped' => false, // This field is not mapped to any property of the entity
                'required' => true, // Not required, as it's an optional field
            ])
            ->add('Submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-success m-2']
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class' => Materiel::class,
        ]);
    }
}