<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class,[
                'attr' =>['class' => 'form-control', 'placeholder' => "Login"],
                'label' => "Nom utilisateur"
            ])
            ->add('roles', ChoiceType::class,[
                'choices'=>[
                    'Administrateur' => 'ROLE_ADMIN',
                    'Demandeur' => 'ROLE_DEMANDEUR',
                    'Prestataire' => 'ROLE_PRESTATAIRE',
//                    'Utilisateur' => 'ROLE_USER'
                ],
                'multiple'=> true,
                'expanded'=>true,
            ])
            ->add('password', PasswordType::class,[
                'attr' => ['class' => 'form-control', 'placeholder' => 'Mot de passe'],
                'label' => "Mot de passe"
            ])
//            ->add('connexion')
//            ->add('lastConnectedAt', null, [
//                'widget' => 'single_text',
//            ])
//            ->add('clientIp')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
