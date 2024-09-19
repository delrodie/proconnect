<?php

namespace App\Form;

use App\Entity\Slide;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class SlideType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class,[
                'attr'=>['class'=>"form-control"],
                'label' => "Titre du slide"
            ])
            ->add('content', TextareaType::class,[
                'attr' => ['class' => 'form-control', 'rows'=>7],
                'label' => "Le contenu"
            ])
            ->add('media', FileType::class,[
                'attr'=>['class'=>"dropify-fr", 'data-preview' => ".preview"],
                'label' => "Téléchargez votre photo de profile",
                'mapped' => false,
                'multiple' => false,
                'constraints' => [
                    new File([
                        'maxSize' => "20000k",
                        'mimeTypes' =>[
                            'image/png',
                            'image/jpeg',
                            'image/jpg',
                            'image/gif',
                            'image/webp',
                        ],
                        //'mimeTypesMessage' => "Votre fichier doit être de type image",
                        //'maxSizeMessage' => "La taille de votre image doit être inférieure à 2Mo",
                    ])
                ],
                'required' => false,
            ])
            ->add('statut', CheckboxType::class,[
                'attr' => ['class' => 'form-check-input'],
                'label_attr' => ['class' => 'form-check-label'],
                'label' => 'Activer / désactiver le slide',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Slide::class,
        ]);
    }
}
