<?php

namespace App\Form;

use App\Entity\Country;
use App\Entity\Geographical;
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
        ->add('intitule', TypeTextType::class,[ 'attr'=>['class'=>'form-control']])
        ->add('geographical', EntityType::class, ['class' => Geographical::class, 'choice_label' => 'location', 'attr' => ['class' => 'form-control select-search']])
        ->add('Country', EntityType::class, ['class' => Country::class, 'choice_label' => 'intitule', 'attr' => ['class' => 'form-control']])
       
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Region::class,
        ]);
    }
}
