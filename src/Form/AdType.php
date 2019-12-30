<?php

namespace App\Form;

use App\Entity\Ad;
use App\Form\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdType extends ApplicationType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfiguration("Titre","Tapez un Titre séduisant pour votre annonce"))
            ->add('slug', TextType::class, $this->getConfiguration("Adresse web", "Tapez une adresse web (automatique) " , [ 'required' => false]))
            ->add('introduction', TextType::class, $this->getConfiguration("Introduction","Donnez une description globale de l'annonce"))
            ->add('coverImage', UrlType::class, $this->getConfiguration("Url de l'image principale ", "Donnez l'adresse de l'image qui donne vraiment envie "))
            ->add('content', TextareaType::class, $this->getConfiguration("Description détailée","Tapez une description qui donne vraiment envie de venir chez vous !"))
            ->add('price', MoneyType::class, $this->getConfiguration("Prix par nuit", "Indiquez le prix  que vous voulez pour une nuit "))
            ->add('rooms',IntegerType::class, $this->getConfiguration("Nombre de chambre","Le nombre de chambres disponibles "))
            ->add('images', CollectionType::class,[
                'entry_type' => ImageType::class,
                'allow_add' => true,
                'allow_delete' => true



            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
