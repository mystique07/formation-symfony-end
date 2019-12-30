<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends ApplicationType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, $this->getConfiguration('Prénom','Votre prénom ...'))
            ->add('lastName', TextType::class, $this->getConfiguration('Nom','Votre nom ...'))
            ->add('email', EmailType::class , $this->getConfiguration('Email','Tapez votre email ...'))
            ->add('picture', UrlType::class, $this->getConfiguration('Photo de profil', 'Entrer une photo de profil'))
            ->add('hash', PasswordType::class, $this->getConfiguration('Mot de passe', 'Saisir un mot de passe de plus de 8 caractères en Maj. Min. Chiffre et Symbole'))
            ->add('passwordConfirm',PasswordType::class, $this->getConfiguration('Confirmation de mot de passe', 'Veuillez confirmer votre mot de passe'))
            ->add('introduction', TextType::class, $this->getConfiguration('Décriver vous', 'Décriver en quelque mot ...'))
            ->add('description', TextareaType::class, $this->getConfiguration('Description détaillée', 'Votre description détaillée ...'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
