<?php

namespace App\Form;

use App\Entity\Excursion;
use App\Entity\GoodAddress;
use App\Entity\Region;
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

class ExcursionAgenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder


            ->add('title', TypeTextType::class, ['attr' => ['class' => 'form-control']])
            ->add('description', TextareaType::class, ['attr' => ['class' => 'form-control']])
            ->add('inclus', TextareaType::class, ['attr' => ['class' => 'form-control']])
            ->add('nonInclus', TextareaType::class, ['attr' => ['class' => 'form-control']])

            /*             ->add('country', EntityType::class, ['class' => Country::class, 'choice_label' => 'entitled', 'attr' => ['class' => 'form-control']])
           */->add('regions', EntityType::class, array(
                'class'     => Region::class,
                'choice_label' => 'intitule',
                'group_by'  => 'country.intitule',
                'multiple'  => true,
                'attr' => ['class' => 'form-control select-search'],
            ))
            /*   ->add('country', EntityType::class, array(
                'class'     => Country::class,s
                'choice_label' => 'entitled',
                'multiple'  => true,
                'attr' => ['class' => 'form-control'],
            )) */
            ->add('goodAddress', EntityType::class, array(
                'class'     => GoodAddress::class,
                'choice_label' => 'intitule',
                'multiple'  => true,
                'attr' => ['class' => 'form-control select'],
            ))
/*             ->add('agence', EntityType::class, ['class' => Agence::class, 'choice_label' => 'name', 'attr' => ['class' => 'form-control']])
 */            ->add('picture', FileType::class,  array('data_class' => null, 'required' => false, 'label' => 'Picture'))
            /*             ->add('reviews', EntityType::class, ['class' => Reviews::class, 'choice_label' => 'comment', 'attr' => ['class' => 'form-control']])
 */
            ->add('images', FileType::class, [
                'label' => 'Pictures',
                'multiple' => true,
                'mapped' => false,
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Excursion::class,
        ]);
    }
}
