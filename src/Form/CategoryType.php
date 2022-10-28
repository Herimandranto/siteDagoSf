<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'required' => 'true',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'exemple: Relation Client, Dev web, ... '
                ]
            ])
            ->add('code', TextType::class, [
                'required' => 'true',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'abréviation ou sigle'
                ]
            ])
            ->add('imageFile', VichFileType::class, [
                'required' => false,
                'download_label' => false,
                'allow_delete' => true,
                'delete_label'   => true,
                'label' => 'Fichier',
                'label_attr' => [
                    'class' => '',
                ],
                'attr' => [
                    'class' => 'form-control',
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
