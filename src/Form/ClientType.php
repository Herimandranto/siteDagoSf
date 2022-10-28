<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => 'true',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nom du client'
                ]
            ])
            ->add('fonction', TextType::class, [
                'required' => 'true',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'fonction, ex: manager'
                ]
            ])
            ->add('message', TextType::class, [
                'required' => 'true',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'le message d\'appuie'
                ]
            ])
            ->add('avatarFile', VichFileType::class, [
                'required' => true,
                'download_label' => false,
                'allow_delete' => true,
                'delete_label'   => true,
                'label' => 'Fichier',
                'label_attr' => [
                    'class' => '',
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
