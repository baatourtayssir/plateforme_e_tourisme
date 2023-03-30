<?php

namespace App\Form;

use App\Entity\Cruise;
use App\Entity\Excursion;
use App\Entity\Offer;
use App\Entity\OfferExcursion;
use App\Entity\Travel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class OfferExcursionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prix', TypeTextType::class, ['attr' => ['class' => 'form-control']])

            ->add('excursion', EntityType::class, ['class' => Excursion::class, 'choice_label' => 'title', 'attr' => ['class' => 'form-control select-search']])
            ->add('offer', EntityType::class, array(
                'class'     => Offer::class,
                'choice_label' => 'title',
                'multiple'  => true,
                'attr' => ['class' => 'form-control select-search'],
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OfferExcursion::class,
        ]);
    }
}
