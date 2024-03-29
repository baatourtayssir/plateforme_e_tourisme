<?php

namespace App\Form;

use App\Entity\Offer;
use App\Entity\Reservation;
use App\Entity\Agence;
use App\Entity\Client;
use App\Entity\PriceList;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Doctrine\ORM\EntityRepository;





class ReservationOfferType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {



        $builder


            /*   ->add('priceList', EntityType::class, [
                'class' => PriceList::class,
                'choice_label' => 'name',
                'placeholder' => 'Choose a price list',
                'required' => true,
                'label' => 'Price List',
                'choices' => [],
                'query_builder' => function (EntityRepository $repository) use ($options) {
                    $offer = $options['data']->getOffer();

                    return $repository->createQueryBuilder('pl')
                        ->andWhere('pl.offer = :offer')
                        ->setParameter('offer', $offer);
                },
            ]) */
            ->add('priceList', EntityType::class, ['class' => PriceList::class, 'choice_label' => 'title', 'group_by'  => 'offer.title', 'attr' => ['class' => 'form-control select-search']]);
    }

    public function setDefaultChoices(OptionsResolver $resolver, $choices): void
    {
        $resolver->setDefault('choices', $choices);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
