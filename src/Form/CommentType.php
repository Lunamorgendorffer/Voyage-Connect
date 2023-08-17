<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        
            ->add('text', TextareaType::class,[
                    'label' => 'Your Comment',
                
                    'attr'=>[
                        'class'=>'form-control',
                        'placeholder' => 'Please write your comment here...'
                    ]
                ])
            ->add('submit', SubmitType::class,[ // Champ de téléchargement de l'image du post
                'label' => 'Send',
                'attr'=>['class'=>'create-account']]) // Bouton de soumission du formulaire
        
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
