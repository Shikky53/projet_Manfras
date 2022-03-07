<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class EditPseudoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('plainPseudo',RepeatedType::class,[
                'type' => TextType::class,
                'invalid_message' => 'Le pseudo doit correspondre dans les 2 champs.',
                'mapped' => false,
                'required' => false,
                'attr' => ['autocomplete' => 'new-pseudo'],
                'first_options' => [
                    'label' => 'Nouveau pseudo',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Le pseudo est requis dans ce champs'
                        ]),
                    ],
                ],
                'second_options' => [
                    'label' => 'Confirmer votre nouveau pseudo',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'La confirmation du pseudo est requis'
                        ]),
                    ],
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
