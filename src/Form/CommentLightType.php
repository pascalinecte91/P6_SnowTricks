<?php

namespace App\Form;

use App\Entity\Comment;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentLightType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'votre email',
                'attr' => [
                    'class' => 'email'
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Votre commentaire',
                'attr' => []
            ])

            /*->add('createdAt', DateType::class, [
                "widget" => 'choice'
            ])*/

            /*->add('category', EntityType::class,[
                'class'=> Category::class,
                'choice_label'=> 'title',
                'required'=> false, 
            ])*/
            ->add('rgpd', CheckboxType::class)
            ->add('parentid', HiddenType::class, [
                'mapped' => false
            ])
            ->add('envoyer', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
