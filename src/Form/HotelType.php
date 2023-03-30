<?php

namespace App\Form;

use App\Entity\Hotel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Region;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class HotelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('intitule', TypeTextType::class, ['attr' => ['class' => 'form-control']])
            ->add('address', TypeTextType::class, ['attr' => ['class' => 'form-control']])
            /*             ->add('country', EntityType::class, ['class' => Country::class, 'choice_label' => 'intitule' , 'attr' => ['class' => 'form-control']])
 */
            ->add('region', EntityType::class, ['class' => Region::class, 'choice_label' => 'intitule', 'group_by'  => 'country.intitule', 'attr' => ['class' => 'form-control select-search']])

            ->add('characteristic', TextareaType::class, ['attr' => ['class' => 'form-control']])
            ->add('picture', FileType::class,  array('data_class' => null, 'required' => false, 'label' => 'Picture'))
            ->add('images', FileType::class, [
                'label' => 'Pictures',
                'multiple' => true,
                'mapped' => false,
                'required' => false
            ]);;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Hotel::class,
        ]);
    }
}
