<?php

namespace App\Form;

use App\Entity\Text1;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class Text1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title1', TextType::class, [
                'required' => 'true',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'titre de la première colonne'
                ]
            ])
            ->add('title2', TextType::class, [
                'required' => 'true',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'titre de la 2ème colonne'
                ]
            ])
            ->add('title3', TextType::class, [
                'required' => 'true',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'titre de la 3ème colonne'
                ]
            ])
            ->add('title4', TextType::class, [
                'required' => 'true',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'titre de la 4ème colonne'
                ]
            ])
            ->add('text1', TextType::class, [
                'required' => 'true',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'contenu du texte de la première colonne'
                ]
            ])
            ->add('text2', TextType::class, [
                'required' => 'true',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'contenu du texte de la 2ème colonne'
                ]
            ])
            ->add('text3', TextType::class, [
                'required' => 'true',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'contenu du texte de la 3ème colonne'
                ]
            ])
            ->add('text4', TextType::class, [
                'required' => 'true',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'contenu du texte de la 4ème colonne'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Text1::class,
        ]);
    }
}
