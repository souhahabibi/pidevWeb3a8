<?php

namespace App\Form;

use App\Entity\Salle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SalleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('description')
            ->add('lieu')
            ->add('image', FileType::class, [ // Change this to FileType
                'label' => 'Image',
                'mapped' => false, // This field is not mapped to any property of the entity
                'required' => false, // Not required, as it's an optional field
            ])
            ->add('Submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-success m-2']
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Salle::class,
        ]);
    }
}
