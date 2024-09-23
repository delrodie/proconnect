<?php

namespace App\Form;

use App\Entity\Demandeur;
use App\Entity\Message;
use App\Entity\Prestataire;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MessageFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextType::class,[
                'attr' => ['class' => 'form-control form-control-lg', 'placeholder' => "Écrivez votre message...",
                    'aria-label' => "Écrivez votre message", 'autocomplete' => "off"
                ]
            ])
//            ->add('createdAt', null, [
//                'widget' => 'single_text',
//            ])
//            ->add('vue')
//            ->add('vueAt', null, [
//                'widget' => 'single_text',
//            ])
//            ->add('reference')
//            ->add('demandeur', EntityType::class, [
//                'class' => Demandeur::class,
//                'choice_label' => 'id',
//            ])
//            ->add('prestataire', EntityType::class, [
//                'class' => Prestataire::class,
//                'choice_label' => 'id',
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }
}
