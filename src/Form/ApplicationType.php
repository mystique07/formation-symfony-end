<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;

class ApplicationType extends AbstractType {

    /**
     * Permet d'avoir la configuration de base d'un champ !
     * @param $title
     * @param $placeholder
     * @param array $options
     * @return array
     */
    protected function getConfiguration($title, $placeholder, $options=[]): array
    {
        return array_merge_recursive( [
            'label' => $title,
            'attr' =>[
                'placeholder' =>$placeholder
            ]
        ], $options
        );
    }

}
