<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'required' => true,
                'label'  => "Adresse email:",
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('roles')
            ->add('password', PasswordType::class, [
                'required' => true,
                'label'  => "Mot de passe :",
                'attr' => [
                    'class' => 'form-control',
                ]
            ])

            ->add('avatarFile', FileType::class, [
                'label' => 'Brochure (PDF file)',

                'mapped' => false,

                'required' => false,

                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/svg+xml',
                            'image/apng',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Veuillez uploader le bon format',
                    ])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
