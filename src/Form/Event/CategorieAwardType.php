<?php

namespace App\Form\Event;

use App\Entity\Event\CategorieAward;
use Sonata\AdminBundle\Form\Type\ModelType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
//use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Sonata\AdminBundle\Form\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategorieAwardType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('nom')             
                ->add('artistes', CollectionType::class, [
                    'entry_type' => 'App\Form\Event\ArtisteType',
                    'by_reference' => false,
                    'allow_add' => true,
                    'allow_delete' => true,
                     'prototype' => true,
                    'required' => true,
                    'sonata_admin' => true,
               ])
                
                
        ;
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => CategorieAward::class,
        ]);
    }

}
