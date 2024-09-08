<?php

namespace App\Form;

use App\Entity\Competence;
use App\Entity\Prestataire;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class PrestataireFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->add('matricule')
            ->add('nom', TextType::class,[
                'attr' => ['class' => 'form-control form-control-lg', 'autocomplete' => 'off', 'placeholder' => "Nom de famille"]
            ])
            ->add('prenoms', TextType::class,[
                'attr' => ['class' => 'form-control form-control-lg', 'autocomplete' => 'off', 'placeholder' => "Prenoms"]
            ])
            ->add('sexe', ChoiceType::class,[
                'attr' => ['class' => 'form-select'],
                'choices' => [
                    '-- Sexe -- ' => '',
                    'Homme' => 'HOMME',
                    'Femme' => 'FEMME'
                ],
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('email', EmailType::class,[
                'attr' => ['class' => 'form-control form-control-lg', 'placeholder' => 'Adresse email', 'autocomplete'=>'off']
            ])
            ->add('telephone', TelType::class,[
                'attr' => ['class' => 'form-control form-control-lg', 'placeholder' => 'Numéro de telephone', 'autocomplete'=>'off']
            ])
            ->add('profession', TextType::class,[
                'attr' => ['class' => 'form-control form-control-lg', 'placeholder' => 'Profession', 'autocomplete'=>'off']
            ])
            ->add('adresse', TextType::class,[
                'attr' => ['class' => 'form-control form-control-lg', 'placeholder' => 'Adresse géographique', 'autocomplete'=>'off']
            ])
//            ->add('geolocalisation', TextType::class,[
//                'attr' => ['class' => 'form-control form-control-lg', 'placeholder' => 'Géolocalisation', 'autocomplete'=>'off']
//            ])
            ->add('niveau', TextType::class,[
                'attr' => ['class' => 'form-control form-control-lg', 'placeholder' => 'Niveau', 'autocomplete'=>'off']
            ])
            ->add('experience', ChoiceType::class,[
                'attr' => ['class' => 'form-select'],
                'choices' => [
                    '-- Expérience professionnelle --' => '',
                    '1 - 3 ans' => 1,
                    '4 - 10 ans' => 2,
                    '10 ans +' => 3,
                ],
                'multiple' => false,
                'expanded' => false
            ])
            ->add('langue', TextType::class,[
                'attr' => ['class' => 'form-control form-control-lg', 'placeholder' => 'Langues parlées', 'autocomplete'=>'off']
            ])
            ->add('tarifHoraire', IntegerType::class,[
                'attr' => ['class' => 'form-control form-control-lg', 'placeholder' => 'Tarif horaire', 'autocomplete'=>'off']
            ])
            ->add('stock', ChoiceType::class,[
                'attr' => ['class' => 'form-select', 'placeholder'=>"Moyen de déplacement"],
                'choices' => [
                    '-- Stock de pièces détachées --' => '',
                    'Oui' => 'OUI',
                    'Non' => 'NON'
                ],
                'multiple' => false,
                'expanded' => false,
                'label' => 'Moyen de déplacement'
            ])
            ->add('deplacement', ChoiceType::class,[
                'attr' => ['class' => 'form-select', 'placeholder'=>"Moyen de déplacement"],
                'choices' => [
                    '-- Moyen de déplacement --' => '',
                    'Véhicule personnel' => 'PERSONNEL',
                    'Transport commun' => 'COMMUN',
                    'Les deux' => 'DEUX'
                ],
                'multiple' => false,
                'expanded' => false,
                'label' => 'Moyen de déplacement'
            ])
            ->add('paiement', ChoiceType::class,[
                'attr' => ['class' => 'form-select', 'placeholder'=>"Mode de paiement"],
                'choices' => [
                    '-- Mode de paiement --' => '',
                    'Espèce' => 'ESPECE',
                    'Paiement mobile' => 'MOBILE',
                    'Banque' => 'BANQUE',
                    'Les trois modes' => 'TOUS LES MODES',
                ],
                'multiple' => false,
                'expanded' => false,
                'label' => "Mode de paiement"
            ])
            ->add('garantie', TextType::class,[
                'attr' => ['class' => 'form-control form-control-lg', 'placeholder' => 'Durée de garantie', 'autocomplete'=>'off']
            ])
            ->add('casier', FileType::class,[
                'attr' => ['class' => 'form-control'],
                'label' => 'Documents complémentaires (Casier judiciaire)',
                'mapped' => false,
                'required' => false
            ])
            ->add('modeTravail', ChoiceType::class,[
                'attr' => ['class' => 'form-select', 'placeholder'=>"Mode de travail"],
                'choices' => [
                    '-- Méthode de travail --' => '',
                    'Travail seul' => 'SEUL',
                    'Travail avec apprentis' => 'APPRENTIS',
                ],
                'multiple' => false,
                'expanded' => false
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
            ->add('licence', FileType::class,[
                'attr' => ['class' => 'form-control'],
                'label' => 'Téléchargez votre licence de travail',
                'mapped' => false,
                'required' => false
            ])
            ->add('competence', EntityType::class, [
                'class' => Competence::class,
                'choice_label' => 'title',
                'multiple' => true,
                'attr' => ['class' => 'form-select select-2'],
                'label' => "Compétences",
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('c')->orderBy('c.title', 'ASC');
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Prestataire::class,
        ]);
    }
}
