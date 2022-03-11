<?php

namespace App\Form;

use App\Entity\Manga;
use App\Entity\Chapitre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ChapitreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('manga',EntityType::class,[
            //     'required' => false,
            //     'label' => 'Nom du manga',
            //     'class' => Manga::class,
            //     'placeholder' => '-- Sélectionner le nom du manga --',
            //     'choice_label' => 'nom'
            // ])
            ->add('numero',TextType::class,[
                'required' => false,
                'label' => 'Numero du Chapitre',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chapitre::class,
        ]);
    }
}
