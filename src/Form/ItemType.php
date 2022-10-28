<?php

namespace App\Form;

use App\Entity\Item;
use Symfony\Component\Form\AbstractType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'required' => 'true',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'ex: appel sortant, Création de site web,...'
                ]
            ])
            ->add(
                'price',
                TextType::class,
                [
                    'required' => 'true',
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'tarif ou coût du service '
                    ]
                ]
            )

            ->add('imageFile', VichFileType::class, [
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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
        ]);
    }
}
