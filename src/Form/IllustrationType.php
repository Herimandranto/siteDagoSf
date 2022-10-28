<?php

namespace App\Form;

use App\Entity\Illustration;
use Symfony\Component\Form\AbstractType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class IllustrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class, [
                'required' => 'true',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'titre, ex: nous sommes DAGOUTSOURCING'
                ]
            ]) 
            ->add('texte1',TextareaType::class, [
                'required' => 'true',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'ex: résumé ou historique de dago'
                ]
            ])
            ->add('texte2',TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'deuxième paragraphe'
                ]
            ])
            ->add('texte3',TextType::class, [
                'required' => false,
                'attr' => [

                    'class' => 'form-control',
                    'placeholder' => 'troisième paragraphe'
                ]
            ])
            ->add('imageFile', VichFileType::class, [
                'required' => true,
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
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Illustration::class,
        ]);
    }
}
