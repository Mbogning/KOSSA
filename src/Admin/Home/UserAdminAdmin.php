<?php

declare(strict_types=1);

namespace App\Admin\Home;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

final class UserAdminAdmin extends AbstractAdmin
{

    protected $baseRoutePattern = 'home/administrateur';
    protected $baseRouteName = 'home_administrateur';
    
       public function createQuery($context = 'list') {
        $query = parent::createQuery($context);
        $query->orWhere(
                $query->expr()->like($query->getRootAliases()[0] . '.roles', ':role1')
        );
        $query->orWhere(
                $query->expr()->like($query->getRootAliases()[0] . '.roles', ':role2')
        );
        $query->orWhere(
                $query->expr()->like($query->getRootAliases()[0] . '.roles', ':role3')
        );
        $query->orWhere(
                $query->expr()->like($query->getRootAliases()[0] . '.roles', ':role4')
        );
        $query->orWhere(
                $query->expr()->like($query->getRootAliases()[0] . '.roles', ':role5')
        );
        $query->orWhere(
                $query->expr()->like($query->getRootAliases()[0] . '.roles', ':role6')
        );
        $query->setParameter('role1', '%ROLE_SUPER_ADMIN%');
        $query->setParameter('role2', '%ROLE_ADMIN%');
        $query->setParameter('role3', '%ROLE_ADMIN_PLAY%');
        $query->setParameter('role4', '%ROLE_ADMIN_EVENT%');
        $query->setParameter('role5', '%ROLE_ADMIN_NEWS%');
        $query->setParameter('role6', '%ROLE_ADMIN_MOVIE%');
        return $query;
           }

 
    
    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('nom')
            ->add('username')
            ->add('email')
            ->add('roles')
            ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->addIdentifier('nom', NULL, ['editable' => true])
            ->add('username')
            ->add('email')
            ->add('roles');
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->add('nom')
            ->add('pseudo')
            ->add('email')
            ->add('description')
            ->add('plainPassword', RepeatedType::class, array(
                   // 'type' => PasswordType::class,
                    'first_options' => array('label' => 'Mot de passe'),
                    'second_options' => array('label' => 'Repeter mot de passe'),
                    'invalid_message' => 'les mots de passe sont differents',
                    'required' => false
                ))
            ->add('sexe', ChoiceType::class, [
                    'required' => false,
                    'choices' => [
                        'Masculin' => 'masculin',
                        'Féminin' => 'feminin',
                    ],
                    'placeholder' => 'Choisir le type',
                ])
            ->add('roles', ChoiceType::class, [
                    'choices' => [
                        'Super administrateur' => 'ROLE_SUPER_ADMIN',
                        'Administrateur' => 'ROLE_ADMIN',
                        'Manager Play' => 'ROLE_ADMIN_PLAY',
                        'Manager Event' => 'ROLE_ADMIN_EVENT',
                        'Manager News' => 'ROLE_ADMIN_NEWS',
                        'Manager Movie' => 'ROLE_ADMIN_MOVIE',
                    ],
                    'multiple' => true,
                    'expanded' => true
                ])
            ->add('photo', MediaType::class, array(
                    'provider' => 'sonata.media.provider.image',
                    'context' => 'home_user_photo',
                    'required' => false,
                    'label' => "Photo de profil",
                ))
           
            ;
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add('id')
            ->add('nom')
            ->add('username')
            ->add('email')
            ->add('password')
            ->add('roles')
            ;
    }
    
}
