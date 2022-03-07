<?php

namespace App\Form;

use App\Entity\Scan;
use App\Entity\Chapitre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ScanType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numero',TextType::class,[
                
            ])
            ->add('chapitre',EntityType::class,[
                'required' => false,
                'label' => 'Numero du chapitre',
                'class' => Chapitre::class,
                'placeholder' => '-- Sélectionner le chapitre concernez --',
                'choice_label' => 'numero'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Scan::class,
        ]);
    }
}
