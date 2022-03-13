<?php

namespace App\Form;

use App\Entity\Scan;
use App\Entity\Chapitre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ScanType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numero')
            ->add('scan',FileType::class,[
                'mapped' => false,
                'required' => false,
                'label' => 'Page',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ajouter la page'
                    ]),
                    new File([
                        'maxSize' => '900k',
                        'maxSizeMessage' => 'Le poids ne peut dépasser 1mo. Votre fichier est trop lourd.'
                    ])
                ]
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
