<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email address',
                'required' => true,
                'attr' => [
                    'placeholder' => 'julie@daki-suki.com'
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Firstname',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Please fill in the field'
                ],
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Your firstname should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 32,
                    ]),
                    new NotBlank([
                        'message' => 'Please enter a firstname',
                    ]),
                ]
            ])
            ->add('lastname',TextType::class, [
                'label' => 'Lastname',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Please fill in the field'
                ],
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Your lastname should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 32
                    ]),
                    new NotBlank([
                        'message' => 'Please enter a firstname'
                    ])
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'I consent to be more 18',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.'
                    ]),
                ]
            ])
            ->add('plainPassword', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'type' => PasswordType::class,
                'invalid_message' => 'The  password is incorrect.',
                'mapped' => false,
                'required' => true,
                'first_options' => ['label' => 'Password',
                    'attr' => [
                        'placeholder' => '**********'
                    ]],
                'second_options' => ['label' => 'Repeat the password',
                    'attr' => [
                        'placeholder' => '**********'
                    ]],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 128,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
