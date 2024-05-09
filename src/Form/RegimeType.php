<?php

namespace App\Form;

use App\Entity\Regime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use MeteoConcept\HCaptchaBundle\Form\HCaptchaType;



class RegimeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('startdate')
            ->add('enddate')
            ->add('duration')
            ->add('description')
            ->add('goal', ChoiceType::class, [
                'choices'  => [
                    'Healthy_lifestyle' => 'Healthy_lifestyle',
                    'Gaining_weight' => 'Gaining_weight',
                    'Losing_weight' => 'Losing_weight',
                ],
               
            ])  
           /* ->add('captcha', HCaptchaType::class, [
                'label' => 'Anti-bot test',
                // optionally: use a different site key than the default one:
                'hcaptcha_site_key' => '88687d74-8332-48a9-9782-80ebf3a8fc30',
            ])*/
        ;
         
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Regime::class,
        ]);
    }
}
