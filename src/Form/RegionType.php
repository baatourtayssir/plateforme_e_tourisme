<?php

namespace App\Form;

use App\Entity\Country;
use App\Entity\Region;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;

class RegionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('entitled', TypeTextType::class,[ 'attr'=>['class'=>'form-control']])
        ->add('category', TypeTextType::class,[ 'attr'=>['class'=>'form-control']])
        ->add('Country', EntityType::class, ['class' => Country::class, 'choice_label' => 'entitled'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Region::class,
        ]);
    }
}