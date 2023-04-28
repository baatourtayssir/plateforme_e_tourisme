<?php

namespace App\Form;

use App\Entity\Hotel;
use App\Entity\PriceList;
use App\Entity\offer;
use App\Entity\Excursion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class PriceListTravelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TypeTextType::class, ['attr' => ['class' => 'form-control']])

            ->add('prix', TypeTextType::class, ['attr' => ['class' => 'form-control'],'label' => 'Price'])
            ->add('dateDebut', DateType::class, [
                'widget' => 'choice',
                'input'  => 'datetime_immutable'
            ])
            ->add('dateFin', DateType::class, [
                'widget' => 'choice',
                'input'  => 'datetime_immutable'

            ])
            
            ->add('hotels', EntityType::class, array(
                'class'     => Hotel::class,
                'choice_label' => 'intitule',
                'multiple'  => true,
                'attr' => ['class' => 'form-control select'],
            ))
           
            ->add('Included_excursions', EntityType::class, array(
                'class'     => Excursion::class,
                'choice_label' => 'title',
                'label' => 'Excursion included',
                'multiple'  => true,
                'attr' => ['class' => 'form-control select'],
            ))
/*             ->add('offer', EntityType::class, ['class' => Offer::class, 'choice_label' => 'title', 'attr' => ['class' => 'form-control select-search']]);
 */   ; }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PriceList::class,
        ]);
    }
}
