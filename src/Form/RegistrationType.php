<?php

namespace App\Form;

use function PHPSTORM_META\type;
use App\Entity\Home\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('nom', TextType::class, [
            //'label' => 'label.nom',
        ])
            ->add('sexe', ChoiceType::class, [
                'choices' => [
                    'Entrer votre sexe' =>'placeholder',
                    'Masculin' => 'M',
                    'Feminin' => 'F',
                ],
                //'label' => 'label.sexe',
            ])
        ->add('email', EmailType::class, [
            //'label' => 'label.email',
        ])
        ->add('telephone', TextType::class, [
            //'label' => 'label.telephone',
        ])
        ->add('password', RepeatedType::class, array(
            'type' => PasswordType::class,
            'first_options'  => array('label' => 'Mot de passe', 'error_bubbling' => true),
            'second_options' => array('label' => 'Confirmer le mot de passe'),
            'invalid_message' => 'Les 2 mots de passe ne sont pas identiques.',
        ))
        ->add('save', SubmitType::class, ['label' => 'Envoyer']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
