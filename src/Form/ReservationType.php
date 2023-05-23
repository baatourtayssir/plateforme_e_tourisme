<?php

namespace App\Form;

use App\Entity\Offer;
use App\Entity\Reservation;
use App\Entity\Agence;
use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use App\Entity\PriceList;
use Doctrine\ORM\EntityRepository;




class ReservationType extends AbstractType
{
    /*   private $reservations;
    public function __construct(array $reservations = [])
    {
        $this->reservations = $reservations;
    } */

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {



        $builder

            ->add('client', EntityType::class, [
                'class' => Client::class,
                'choice_label' => 'lastname',
                'label' => 'User Last Name',
            ])

            ->add('offer', EntityType::class, ['class' => Offer::class, 'choice_label' => 'title', 'attr' => ['class' => 'form-control select-search']])
            /* ->add('priceList', EntityType::class, [
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
            ->add('priceList', EntityType::class, ['class' => PriceList::class, 'choice_label' => 'title','group_by'  => 'offer.title', 'attr' => ['class' => 'form-control select-search']])

            ;
    }

    public function setDefaultChoices(OptionsResolver $resolver, $choices): void
    {
        $resolver->setDefault('choices', $choices);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
            /*  'reservations' => null, */
        ]);
    }
}
