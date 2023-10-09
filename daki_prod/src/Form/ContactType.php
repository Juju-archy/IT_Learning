<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastname', TextType::class, [
                'label' => 'Lastname',
                'attr'=> [
                    'placeholder' => 'Micheal'
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Firstname',
                'attr'=> [
                    'placeholder' => 'Smith'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Your email',
                'attr'=> [
                    'placeholder' => 'smith.b@daki-suki.com'
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Your message',
                'attr' => [
                    'placeholder' => 'How can we help you?'
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'I\'m not a robot... maybe!',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to send message..'
                    ]),
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Send',
                'attr' => [
                    'class' => 'btn-block btn-success'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
