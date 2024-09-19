<?php

namespace App\Form;

use App\Entity\Postuler;
use App\Entity\Projet;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostulerFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->add('reference')
            ->add('facturation', IntegerType::class,[
                'attr' => ['class' => 'form-control form-control-lg money-input', 'autocomplete' => 'off'],
                'label' => "Montant de la prestation",
//                'currency' => "XOF"
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
            ->add('garantie', TextType::class,[
                'attr' => ['class' => 'form-control form-control-lg', 'autocomplete' => 'off'],
                'label' => "Durée de garantie"
            ])
            ->add('delai', null, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control form-control-lg'],
                'label' => 'Délai d\'exécution '
            ])
            ->add('description', TextareaType::class,[
                'attr' => ['class' => 'form-control', 'rows'=>7],
                'label' => "Description de votre offre au demandeur"
            ])
//            ->add('createdAt', null, [
//                'widget' => 'single_text',
//            ])
//            ->add('projet', EntityType::class, [
//                'class' => Projet::class,
//                'choice_label' => 'id',
//            ])
//            ->add('user', EntityType::class, [
//                'class' => User::class,
//                'choice_label' => 'id',
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Postuler::class,
        ]);
    }
}
