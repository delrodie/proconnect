<?php

namespace App\Form;

use App\Entity\ApiClient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ApiClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('entite', TextType::class,[
                'attr' => ['class' => 'form-control', 'autocomplete' => 'off'],
                'label' => "Entité"
            ])
            ->add('domaine', UrlType::class,[
                'attr' => ['class' => 'form-control', 'autocomplete' => "off", 'placeholder' => "https://domaine.com"],
                'label' => "Domaine URL",
                'required' => false
            ])
//            ->add('apiKey')
            ->add('active', CheckboxType::class,[
                'attr' => ['class' => 'form-check-input'],
                'label_attr' => ['class' => 'form-check-label'],
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ApiClient::class,
        ]);
    }
}
