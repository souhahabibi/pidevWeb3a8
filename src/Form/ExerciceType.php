<?php

namespace App\Form;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Exercice;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ExerciceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder
        ->add('nom')
        ->add('etape')
        ->add('image', FileType::class, [
            'label' => 'Image du cours',
            'required' => true, // La rend obligatoire
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
        
        ->add('cours', EntityType::class, [
            'class' => 'App\Entity\Cours',
            'choice_label' => 'nom'])

        ->add('user', EntityType::class, [
            'class' => 'App\Entity\User',
            'choice_label' => 'nom', 
        ]);
}


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Exercice::class,
        ]);
    }
}
