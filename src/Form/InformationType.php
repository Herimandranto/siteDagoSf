<?php

namespace App\Form;

use App\Entity\Information;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class InformationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('adresse', TextType::class,
            [
                'required' => 'true',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Votre adresse'
                ]
            ])
            ->add('phone',TextType::class,
            [
                'required' => 'true',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'ex: +261 34 34 344 34 '
                ]
            ])
            ->add('email',EmailType::class,
            [
                'required' => 'true',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'votre adresse email '
                ]
            ])
            ->add('facebook',TextType::class,
            [
                'required' => 'true',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'ex: https://web.facebook.com/dagoutsourcing '
                ]
            ])
            ->add('skype',TextType::class,
            [
                'required' => 'false',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'lien de votre profil Skype'
                ]
            ])
            ->add('linkedIn',      TextType::class,
            [
                'required' => 'false',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'lien de votre profil linnkedIn'
                ]
            ])
            ->add('wathsapp',TextType::class,
            [
                'required' => 'false',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'votre numero de contact wathsApp'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Information::class,
        ]);
    }
}
