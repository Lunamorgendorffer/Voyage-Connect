<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Messages;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class MessagesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Ajoute un champ "title" de type "TextType" pour saisir le titre du message
        $builder
            ->add('title', TextType::class, [
                "attr" => [
                    "class" => "form-control",
                ]
            ])
            // Ajoute un champ "message" de type "TextareaType" pour saisir le contenu du message (texte multiligne)
            ->add('message', TextareaType::class, [
                "attr" => [
                    "class" => "form-control",
                ]
            ])
            // Ajoute un champ "recipient" de type "EntityType" pour sélectionner le destinataire du message
            ->add('recipient',  EntityType::class, [
                "class" => User::class, // Les choix du champ sont des instances de la classe "User"
                "choice_label" => "pseudo", // Le label des choix est défini en utilisant la propriété "pseudo" de l'entité "User"
                "attr" => [
                    "class" => "form-control", // Ajoute la classe CSS "form-control" pour améliorer le style du champ
                ]
            ])
            // Ajoute un bouton de soumission du formulaire avec le libellé "Envoyer"
            ->add('envoyer', SubmitType::class, [
                "attr" => [
                    "class" => "btn btn-primary", // Ajoute la classe CSS "btn btn-primary" pour styliser le bouton
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Messages::class,
        ]);
    }
}
