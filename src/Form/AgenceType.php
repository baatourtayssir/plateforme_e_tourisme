<?php

namespace App\Form;

use App\Entity\Agence;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Validator\Constraints\File;

class AgenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom', TypeTextType::class,[ 'attr'=>['class'=>'form-control']])
            ->add('Numtel', TypeTextType::class,[ 'attr'=>['class'=>'form-control']])
            ->add('Adresse', TypeTextType::class,[ 'attr'=>['class'=>'form-control']])
            ->add('Description', TextareaType::class,[ 'attr'=>['class'=>'form-control']])
            ->add('Logo',TypeTextType::class,[ 'attr'=>['class'=>'form-control']])
        //    ->add('Logo', FileType::class, [
        //     'label' => 'Logo (PDF file)',

        //     // unmapped means that this field is not associated to any entity property
        //     'mapped' => false,

        //     // make it optional so you don't have to re-upload the PDF file
        //     // every time you edit the Product details
        //     'required' => false,

        //     // unmapped fields can't define their validation using annotations
        //     // in the associated entity, so you can use the PHP constraint classes
        //     'attr'=>['class'=>'form-control']
        // ])
        //    // ->add('LogoFile', VichImageType::class, array('required' => false));
         ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Agence::class,
        ]);
    }
}
