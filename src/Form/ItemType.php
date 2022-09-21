<?php

namespace App\Form;

use App\Entity\Item;
use App\Entity\Category;
use Doctrine\DBAL\Types\FloatType;
use Symfony\Component\Form\AbstractType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('price')
            ->add('description', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => "décrire le produit",
                ]
            ])
            ->add('imageFile', VichFileType::class, [
                'required' => false,
                'download_label' => false,
                'allow_delete' => false,
                'delete_label'   => false,
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
