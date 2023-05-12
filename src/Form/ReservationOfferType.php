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




class ReservationOfferType extends AbstractType
{
    /*   private $reservations;
    public function __construct(array $reservations = [])
    {
        $this->reservations = $reservations;
    } */

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {



        $builder

            /* ->add('client', EntityType::class, [
                'class' => Client::class,
                'choice_label' => 'lastname',
                'label' => 'User Last Name',
            ]) */
            /*  ->add('dateReservation', DateType::class, [
                'widget' => 'choice',
                'input'  => 'datetime_immutable'

            ]) */
            /*  ->add('dateReservation', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date de réservation',
                'html5' => false,
                'format' => 'yyyy-MM-dd HH:mm:ss',
                'attr' => ['class' => 'datetimepicker'],
            ]) */
            /*  ->add('status', TextareaType::class, ['attr' => ['class' => 'form-control']]) */
           /*  ->add('priceList', EntityType::class, [
                'class' => PriceList::class,
                'choices' => $options['price_lists'],
                'choice_label' => 'name', // Remplacez 'name' par le champ approprié pour afficher les choix du PriceList
                'placeholder' => 'Select a price list',
            ]) */
            ->add('message', TextareaType::class, ['attr' => ['class' => 'form-control']])
            ->add('note', TextareaType::class, ['attr' => ['class' => 'form-control']])
            /*             ->add('offer', EntityType::class, ['class' => Offer::class, 'choice_label' => 'title', 'attr' => ['class' => 'form-control select-search']])
 */            /*             ->add('agence', EntityType::class, ['class' => Agence::class, 'choice_label' => 'name', 'attr' => ['class' => 'form-control']])
 */;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        /* $resolver->setRequired('price_lists'); */
        $resolver->setDefaults([
            'data_class' => Reservation::class,
            /*  'reservations' => null, */
        ]);
    }
}
