<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Country;
use App\Entity\Geographical;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class CountryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('intitule', TypeTextType::class, ['attr' => ['class' => 'form-control']])
            /*             ->add('category', TypeTextType::class,[ 'attr'=>['class'=>'form-control']])
 */
            ->add('geographical', EntityType::class, ['class' => Geographical::class, 'choice_label' => 'location', 'attr' => ['class' => 'form-control select-search']])
            ->add('picture', FileType::class,  array('data_class' => null, 'required' => false, 'label' => 'Picture'));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Country::class,
        ]);
    }
}
