<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class,[
                'required' => false,
                'label' => 'Adresse Email',
                'attr' => [
                    'placeholder' => 'Entrez votre adresse email'
                ]
            ])
            ->add('file', FileType::class,[
                'mapped' => false,
                'label' => 'Photo de profil',
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Vous devez ajouter une image'
                    ]),
                    new File([
                        'maxSize' => '700k',
                        'maxSizeMessage' => 'Le poids ne peut dépasser 1mo. Votre fichier est trop lourd.'
                    ])
                ]
            ])
            ->add('pseudo', TextType::class,[
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ce pseudo sera visible par les autres utilisateurs'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le pseudo est obligatoire'
                    ]),
                ],
            ])            
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent correspondre.', 
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'required' => false,
                'attr' => [ 'autocomplete' => 'new-password'],
                'first_options' => [
                    'label' => 'Mot de passe',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Un mot de passe est nécessaire',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Your password should be at least {{ limit }} characters',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                    ],
                ],
                'second_options' => [
                    'label' => 'Comfirmez le mot de passe',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Confirmation du mot de passe nécessaire',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Your password should be at least {{ limit }} characters',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                    ],
                    ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
