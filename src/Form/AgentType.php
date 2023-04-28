<?php

namespace App\Form;

use App\Entity\Agent;
use App\Entity\Agence;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class AgentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, ['attr' => ['class' => 'form-control']])

            ->add('password', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password', 'class' => 'form-control'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('lastname', TypeTextType::class, ['attr' => ['class' => 'form-control']])

            ->add('phoneNumber', NumberType::class, [
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Length([
                        'min' => 8,
                        'max' => 8,
                        'exactMessage' => 'Le numéro de téléphone doit contenir exactement {{ limit }} chiffres',
                    ]),
                ],
            ])
                        ->add('adress', TypeTextType::class, ['attr' => ['class' => 'form-control']])
            ->add('Agence', EntityType::class, ['class' => Agence::class, 'choice_label' => 'name', 'attr' => ['class' => 'form-control']])
            ->add('avatar', FileType::class,  array('data_class' => null,'required' => false ,'label' => 'Profile picture'))
                
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    
                    'Super-agent' => 'ROLE_SUPER_AGENT'
                ],
                
                'expanded' => true,
                'multiple' => true,
                'label' => 'Roles',
           
              
                
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Agent::class,
        ]);
    }
}
