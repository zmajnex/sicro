<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SerpFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('keywords')
            ->add('number_of_results', ChoiceType::class, [
                'choices'  => [
                    '5' => 5,
                    '7' => 7,
                    '10' => 10,
                ],
            ])
            ->add('location', ChoiceType::class, [
                'choices'  => [
                    'Great Britain' => "GB",
                    'United States' => "US",
                    'Netherlands' => "NL",
                ],
            ])
            ->add('Get results', SubmitType::class)
        ;
        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
