<?php

namespace App\Form;

use App\Entity\Manga;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MangaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,[
                'required' => false,
                'label' => 'Titre',
                'attr' => [
                    'placeholder' => "Titre du Manga..."
                    ]
            ])
            ->add('file', FileType::class,[
                'mapped' => false,
                'label' => 'Image de couverture',
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
            ->add('nomDuCreateur', TextType::class,[
                'required' => false,
                'label' => 'Nom de l\'auteur',
                'attr' => [
                    'placeholder' => "Placez ici le nom de l'auteur..."
                ]
            ])
            ->add('dessin', TextType::class,[
                'required' => false,
                'label' => 'Dessin',
                'attr' => [
                    'placeholder' => "Nom du Dessinateur..."
                ]
            ])
            ->add('description',TextType::class,[
                'required' => false,
                'label' => 'Description',
                'attr' => [
                    'placeholder' => "Synopsis du manga..."
                ]
            ] )
            ->add('genre', TextType::class,[
                'required' => false,
                'label' => 'Genre',
                'attr' => [
                    'placeholder' => "Donnez le genre de votre manga..."
                ]
            ])
            ->add('statut')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Manga::class,
        ]);
    }
}
