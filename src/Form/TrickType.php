<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Trick;
use DateTimeInterface;
use Doctrine\DBAL\Types\TextType as TypesTextType;
use Doctrine\DBAL\Types\Type;
use phpDocumentor\Reflection\DocBlock\Description;
use phpDocumentor\Reflection\Types\Mixed_;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Mime\MimeTypes;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;

class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
            ->add('name', TextType::class)
            ->add('description', TextareaType::class)
            ->add('category', EntityType::class,[
                'class'=> Category::class,
                'choice_label'=> 'title',
                'required'=> false, 
            ])

            ->add('pictures', CollectionType::class,[
                'entry_type'=> PictureType::class,
                'entry_options' => ['label' => false],
                'label' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false,
                'attr' => [
                    'data-entry-add-class' => 'btn btn-primary',
                    'data-entry-remove-class' => 'btn btn-danger',
                ],     
            ])

            ->add('videos', CollectionType::class,[
                'entry_type'=> VideoType::class,
                'entry_options' => ['label' => false],
                'label' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false,
                'attr' => [
                    'data-entry-add-class' => 'btn btn-primary',
                    'data-entry-remove-class' => 'btn btn-danger',
                ],     
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
