<?php

namespace App\Form;

use App\Entity\GoodAddress;
use App\Entity\Country;
use App\Entity\Region;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class GoodAddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('intitule', TypeTextType::class, ['attr' => ['class' => 'form-control']])
            ->add('region', EntityType::class, ['class' => Region::class, 'choice_label' => 'intitule' , 'attr' => ['class' => 'form-control']])
            ->add('address', TypeTextType::class, ['attr' => ['class' => 'form-control']])
            ->add('description', TextareaType::class, ['attr' => ['class' => 'form-control']])
            ->add('category', TypeTextType::class, ['attr' => ['class' => 'form-control']])
            ->add('picture', FileType::class,  array('data_class' => null, 'required' => false, 'label' => 'Picture'));
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GoodAddress::class,
        ]);
    }
}
