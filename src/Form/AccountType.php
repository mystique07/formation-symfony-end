<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, $this->getConfiguration('Prénom','Modifier votre prénom ...'))
            ->add('lastName', TextType::class,$this->getConfiguration('Nom','Modifier votre nom ...'))
            ->add('email', EmailType::class, $this->getConfiguration('Email','Changer votre email ...'))
            ->add('picture', UrlType::class, $this->getConfiguration('Image de profil','Modifier votre photo de profil'))
            ->add('introduction', TextType::class, $this->getConfiguration('Votre Introduction','Décrivez vous en quelque mot ...'))
            ->add('description', TextareaType::class, $this->getConfiguration('Votre Description','La description détaillée de vous !'))

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
