<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Localite;
use App\Entity\Projet;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //->add('reference')
            ->add('title', TextType::class,[
                'attr' => ['class' => 'form-control', 'autocomplete' => 'off'],
                'label' => 'Intitulé du projet'
            ])
            ->add('lieu', TextType::class,[
                'attr' => ['class' => 'form-control'],
                'label' => 'Lieu de prestation'
            ])
            ->add('datePrestation', null, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control']
            ])
            ->add('dateLimite', null, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control']
            ])
            ->add('preference', ChoiceType::class,[
                'attr' => ['class' => 'form-select'],
                'choices' => [
                    'Aucune' => 'AUCUNE',
                    'Femme' => 'FEMME',
                    'Homme' => 'HOMME'
                ],
                'multiple' => false,
                'expanded' => false
            ])
            ->add('budgetMin', IntegerType::class,[
                'attr' => ['class' => 'form-control'],
                'label' => 'Budget Min.',
                'required' => false
            ])
            ->add('budgetMax', IntegerType::class,[
                'attr' => ['class' => 'form-control'],
                'label' => 'Budget Max.',
                'required' => false
            ])
            ->add('description', TextareaType::class,[
                'attr' => ['class' => 'form-control', 'rows' => 10],
                'label' => 'Description du projet'
            ])
            ->add('statut', ChoiceType::class,[
                'attr' => ['class' => 'form-select'],
                'choices' => [
                    '' => '',
                    'En appel' => 'APPEL',
                    'En réalisation' => 'ENCOURS',
                    'Terminé' => 'TERMINE',
                ],
                'multiple' => false,
                'expanded' => false,
                'required' => false
            ])
//            ->add('createdAt', null, [
//                'widget' => 'single_text',
//            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username',
                'attr' => ['class' => 'form-control', 'readonly' => true],
                'required' => false,
            ])
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'title',
                'attr' => ['class' => 'form-select select2', 'disabled' => false]
            ])
            ->add('localite', EntityType::class, [
                'class' => Localite::class,
                'choice_label' => 'title',
                'attr' => ['class' => 'form-select select2', 'disabled' => false]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Projet::class,
        ]);
    }
}
