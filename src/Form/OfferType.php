<?php

namespace App\Form;

use App\Entity\GoodAddress;
use App\Entity\Offer;
use App\Entity\Reviews;
use App\Entity\Agence;
use App\Entity\Country;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class OfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TypeTextType::class, ['attr' => ['class' => 'form-control']])
            ->add('description', TextareaType::class, ['attr' => ['class' => 'form-control']])
            ->add('country', EntityType::class, ['class' => Country::class, 'choice_label' => 'entitled' , 'attr' => ['class' => 'form-control']])
            ->add('goodAddress', EntityType::class, array(
                'class'     => GoodAddress::class,
                'expanded'  => true,
                'multiple'  => true,
                'attr' => ['class' => 'form-control'],
            ))
            ->add('Agence', EntityType::class, ['class' => Agence::class, 'choice_label' => 'name', 'attr' => ['class' => 'form-control']])
            ->add('picture', FileType::class,  array('data_class' => null, 'required' => false, 'label' => 'Picture'))
            /* ->add('reviews', EntityType::class, ['class' => Reviews::class, 'choice_label' => 'comment', 'attr' => ['class' => 'form-control']])
            */ 
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offer::class,
        ]);
    }
}
