<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Reviews;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TypeTextType::class,[ 'attr'=>['class'=>'form-control']])
            ->add('shortDescription', TextareaType::class, ['attr' => ['class' => 'form-control']])
            ->add('description', TextareaType::class, ['attr' => ['class' => 'form-control']])
            ->add('picture', FileType::class,  array('data_class' => null,'required' => false ,'label' => 'Picture'))
            ->add('categories', EntityType::class, array(
                'class'     => Category::class,
                'choice_label' => 'style',
                'multiple'  => true,
                'attr' => ['class' => 'form-control select-search'],
            ))
            ->add('images', FileType::class,[
                'label' => 'Pictures',
                'multiple' => true,
                'mapped' => false,
                'required' => false
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
