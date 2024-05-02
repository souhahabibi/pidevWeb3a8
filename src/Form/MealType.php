<?php

namespace App\Form;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Meal;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class MealType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('imageUrl', FileType::class, [
                'label' => 'Image',
                'required' => false, // La rend facultative
                'mapped' => false,
                'constraints' => [
                        new Assert\NotBlank([
                            'message' => 'Veuillez télécharger une image.',
                        ]),
                        new Assert\File([
                            'maxSize' => '1024k',
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/png',
                                'image/gif',
                            ],
                            'mimeTypesMessage' => 'Veuillez télécharger une image valide.',
                        ]),
                    ],
                ])
            ->add('recipe')
            ->add('calories')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Meal::class,
        ]);
    }
}
