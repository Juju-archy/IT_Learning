<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Wich name would you use for this address:',
                'attr' => [
                    'placeholder' => 'Address name'
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Firstname:',
                'attr' => [
                    'placeholder' => 'Your firstname'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Lastname:',
                'attr' => [
                    'placeholder' => 'Your lastname'
                ]
            ])
            ->add('company', TextType::class, [
                'label' => 'Company:',
                'attr' => [
                    'placeholder' => "Put '/' if your have not company."
                ]
            ])
            ->add('address', TextType::class, [
                'label' => 'Address:',
                'required' => false,
                'attr' => [
                    'placeholder' => '8 Backer Street...'
                ]
            ])
            ->add('postal', TextType::class, [
                'label' => 'ZipCode:',
                'attr' => [
                    'placeholder' => '271519'
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'City:',
                'attr' => [
                    'placeholder' => 'London'
                ]
            ])
            ->add('country', CountryType::class, [
                'label' => 'Country:',
                'attr' => [
                    'placeholder' => 'England'
                ]
            ])
            ->add('phone', TelType::class, [
                'label' => 'Phone:',
                'attr' => [
                    'placeholder' => '+44 20 1000000'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Validate',
                'attr' => [
                    'class' => 'btn-block btn-info'
                ]
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
