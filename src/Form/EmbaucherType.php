<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Localite;
use App\Entity\Projet;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmbaucherType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->add('reference')
            ->add('title', TextType::class,[
                'attr' => ['class' => 'form-control', 'autocomplete' => 'off'],
                'label' => 'Intitulé du projet'
            ])
            ->add('lieu', TextType::class,[
                'attr' => ['class' => 'form-control', 'autocomplete' => 'off'],
                'label'=> "Adresse géographique"
            ])
            ->add('datePrestation', null, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
                'label' => "Date de prestation"
            ])
            ->add('dateLimite', null, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
                'label' => "Date limite de réponse"
            ])
//            ->add('preference')
            ->add('budgetMin', IntegerType::class,[
                'attr' => ['class' => 'form-control', 'autocomplete' => 'off'],
                'label' => "Budget Min.",
                'required' => false
            ])
            ->add('budgetMax', IntegerType::class,[
                'attr' => ['class' => 'form-control', 'autocomplete' => 'off'],
                'label' => "Budget Max",
                'required' => false
            ])
            ->add('description', TextareaType::class,[
                'attr' => ['class' => 'form-control', 'rows' =>10],
                'label' => "Description du projet"
            ])
            ->add('media', FileType::class,[
                'attr'=>['class'=>"form-control"],
                'label' => "Descriptifs photos (optionnel)",
                'mapped' => false,
                'multiple' => true,
                'required' => false
            ])
//            ->add('statut')
//            ->add('createdAt', null, [
//                'widget' => 'single_text',
//            ])
//            ->add('user', EntityType::class, [
//                'class' => User::class,
//                'choice_label' => 'id',
//            ])
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'title',
                'attr' => ['class' => 'form-select']
            ])
            ->add('localite', EntityType::class, [
                'class' => Localite::class,
                'choice_label' => 'title',
                'attr' => ['class' => 'form-select', 'autocomplete' => 'off'],
                'label' => "Localité"

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
