<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Offer;
use App\Entity\Reviews;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class ReviewsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'lastname',
                'label' => 'User Last Name',
            ])
            ->add('comment', TextareaType::class, ['attr' => ['class' => 'form-control']])
            /*             ->add('Article', EntityType::class, ['class' => Article::class, 'choice_label' => 'title' , 'attr' => ['class' => 'form-control']])

 */
            ->add('date', DateType::class, [
                'widget' => 'choice',
                'input'  => 'datetime_immutable'
            ])
            ->add('picture', FileType::class,  array('data_class' => null, 'required' => false, 'label' => 'Picture'))

            ->add('images', FileType::class, [
                'label' => 'Pictures',
                'multiple' => true,
                'mapped' => false,
                'required' => false
            ])
            ->add('offer', EntityType::class, ['class' => Offer::class, 'choice_label' => 'title', 'attr' => ['class' => 'form-control select-search']]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reviews::class,
        ]);
    }
}
