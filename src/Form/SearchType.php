<?php

namespace App\Form;

use App\Entity\Country;
use App\Model\SearchData;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType as SearchFormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Doctrine\ORM\EntityManagerInterface;

class SearchType extends AbstractType
{

  /*   private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    } */

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('q', SearchFormType::class, [
                'required' => false,
                'label' => 'Title',
                'attr' => [
                    'placeholder' => 'Enter Title',
                ],
            ])
            ->add('country', SearchFormType::class, [
                'required' => false,
                'label' => 'Destination',
                'attr' => [
                    'placeholder' => 'Enter Destination',
                ],
            ])

           /*  ->add('country', ChoiceType::class, [
                'required' => false,
                'label' => 'Country',
                'placeholder' => 'Choose a country',
                'choices' => $this->getCountryChoices(),
                'attr' => [
                    'class' => 'form-control',
                ],
            ]) */

            ->add('submit', SubmitType::class, [
                'label' => 'Search',
                'attr' => [
                    'class' => 'search_tabs pull_left submit_field',
                ],
            ]);
    }

 /*    private function getCountryChoices(): array
    {
        $countries = $this->entityManager->getRepository(Country::class)->findAll();
        $choices = [];
        foreach ($countries as $country) {
            $choices[$country->getIntitule()] = $country->getId();
        }
        return $choices;
    } */

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           /*  'entityManager' => $this->entityManager, */
            'data_class' => SearchData::class,
            'method' => 'POST',
            
        ]);
    }

 
}
