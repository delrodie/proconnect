<?php

namespace App\Form;

use App\Entity\Demandeur;
use App\Entity\Localite;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class DemandeurFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //->add('code')
            ->add('nom', TextType::class,[
                'attr' => ['class' => 'form-control form-control-lg', 'autocomplete' => 'off', 'placeholder' => "Nom de famille"]
            ])
            ->add('prenom', TextType::class,[
                'attr' => ['class' => 'form-control form-control-lg', 'autocomplete' => 'off', 'placeholder' => "Prenoms"]
            ])
            ->add('email', EmailType::class,[
                'attr' => ['class' => 'form-control form-control-lg', 'placeholder' => 'Email', 'autocomplete'=>'off']
            ])
            ->add('telephone', TelType::class,[
                'attr' => ['class' => 'form-control form-control-lg', 'placeholder' => 'Numero de telephone', 'autocomplete'=>'off']
            ])
            ->add('profession', TextType::class,[
                'attr' => ['class' => 'form-control form-control-lg', 'placeholder' => "Profession", 'autocomplete'=>'off']
            ])
            ->add('adresse', TextType::class,[
                'attr' => ['class' => 'form-control form-control-lg', 'placeholder' => "Adresse géographique", 'autocomplete'=>'off']
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
                'required' => false
            ])
            //->add('slug')
//            ->add('createdAt', null, [
//                'widget' => 'single_text',
//            ])
            ->add('localite', EntityType::class, [
                'class' => Localite::class,
                'choice_label' => 'title',
                'attr' => ['class' => 'form-select form-control-lg form-select'],
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('l')->orderBy('l.title', 'ASC');
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Demandeur::class,
        ]);
    }
}
