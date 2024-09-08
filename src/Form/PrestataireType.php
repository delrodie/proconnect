<?php

namespace App\Form;

use App\Entity\Competence;
use App\Entity\Prestataire;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrestataireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('matricule')
            ->add('nom')
            ->add('prenoms')
            ->add('sexe')
            ->add('profession')
            ->add('adresse')
            ->add('geolocalisation')
            ->add('niveau')
            ->add('experience')
            ->add('langue')
            ->add('tarifHoraire')
            ->add('stock')
            ->add('paiement')
            ->add('garantie')
            ->add('casier')
            ->add('modeTravail')
            ->add('media')
            ->add('licence')
            ->add('deplacement')
            ->add('email')
            ->add('telephone')
            ->add('competence', EntityType::class, [
                'class' => Competence::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
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
