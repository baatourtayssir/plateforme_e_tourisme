<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Reviews;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Validator\Constraints\Valid;

class ReviewsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('comment', TextareaType::class, ['attr' => ['class' => 'form-control']])
            ->add('Article', EntityType::class, ['class' => Article::class, 'choice_label' => 'title' , 'attr' => ['class' => 'form-control']])
            ->add('picture', FileType::class,  array('data_class' => null,'required' => false ,'label' => 'Picture'))
           /*  ->add('images', CollectionType::class, [
                'entry_type' => FileType::class,
                'entry_options' => [
                    'required' => false,
                    'constraints' => [
                        new Valid(),
                    ],
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false,
                'required' => false,
            ]) */

            /* ->add('images', FileType::class,[
                'label' => false,
                'multiple' => true,
                'mapped' => false,
                'constraints' => [
                    new Valid(),
                ],
                'required' => false
            ]) */

            ->add('images', FileType::class,[
                'label' => false,
                'multiple' => true,
                'mapped' => false,
                'required' => false
            ])
    
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reviews::class,
        ]);
    }
}
