<?php

declare(strict_types = 1);

namespace App\Admin\Home;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;

final class UserArtisteAdmin extends AbstractAdmin {

    protected $baseRoutePattern = 'home/artiste';
    protected $baseRouteName = 'home_artiste';

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
        $query->setParameter('role1', '%ROLE_MUSICIEN_TALENT%');
        $query->setParameter('role2', '%ROLE_MUSICIEN%');
        $query->setParameter('role3', '%ROLE_ACTEUR%');
        $query->setParameter('role4', '%ROLE_PRODUCTEUR%');
        return $query;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void {
        $datagridMapper
                ->add('id')
                ->add('nom')
                ->add('username')
                ->add('email')
                ->add('password')
                ->add('roles')
                ->add('dateNais')
                ->add('sexe')
                ->add('telephone')
                ->add('anneeDeces')
                ->add('annee80')
                ->add('vues')
                ->add('description')
                ->add('type')

        ;
    }

    protected function configureListFields(ListMapper $listMapper): void {
        $listMapper
                ->addIdentifier('nom', NULL, ['editable' => true])
                ->add('vues');
    }

    protected function configureFormFields(FormMapper $formMapper): void {
        $formMapper
                ->tab('Infos de base')
                ->with('Informations', [
                    'class' => 'col-md-8',
                    'box_class' => 'box box-solid box-primary',
                ])
                ->add('nom')
                ->add('pseudo')
                ->add('plainPassword', RepeatedType::class, array(
                    //'type' => PasswordType::class,
                    'first_options' => array('label' => 'Mot de passe'),
                    'second_options' => array('label' => 'Repeter mot de passe'),
                    'invalid_message' => 'les mots de passe sont differents',
                    'required' => false
                ))
                ->add('dateNais')
                ->add('sexe', ChoiceType::class, [
                    'required' => false,
                    'choices' => [
                        'Masculin' => 'masculin',
                        'Féminin' => 'feminin',
                    ],
                    'placeholder' => 'Choisir le type',
                ])
                ->add('anneeDeces')
                ->end()
                ->with('Accès', [
                    'class' => 'col-md-4',
                    'box_class' => 'box box-solid box-info',
                ])
                ->add('roles', ChoiceType::class, [
                    'choices' => [
                        'Musicien Talent' => 'ROLE_MUSICIEN_TALENT',
                        'Musicien Pro' => 'ROLE_MUSICIEN',
                        'Acteur' => 'ROLE_ACTEUR',
                        'Producteur' => 'ROLE_PRODUCTEUR',
                    ],
                    'multiple' => true,
                    'expanded' => true
                ])
                ->end()
                ->with("Contacts", [
                    'class' => 'col-md-4',
                    'box_class' => 'box box-solid box-info',
                ])
                ->add('email', EmailType::class)
                ->add('telephone', TelType::class)
                ->end()
                ->with('Mots clés', [
                    'class' => 'col-md-4',
                    'box_class' => 'box box-solid box-info',
                ])
                ->add('dateNais')
                ->end()
                ->end()
                ->tab('Présentation')
                ->add('description')
                ->add('photoCouverture', MediaType::class, array(
                    'provider' => 'sonata.media.provider.image',
                    'context' => 'home_user_photo_couverture',
                    'required' => false,
                    'label' => "Photo de couverture",
                ))
                ->add('photo', MediaType::class, array(
                    'provider' => 'sonata.media.provider.image',
                    'context' => 'home_user_photo',
                    'required' => false,
                    'label' => "Photo de profil",
                ))
                ->add('photos', CollectionType::class, [
                    'entry_type' => 'App\Form\MediaUnitType',
                    'by_reference' => false,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'required' => false
                ])
                ->end()
                ->end()
                ->tab('Musique')
                ->with('Informations', [
                    'class' => 'col-md-4',
                    'box_class' => 'box box-solid box-primary',
                ])
                ->add('annee80', null, ['label' => 'Artiste des années 80'])
                ->add('type', ChoiceType::class, [
                    'required' => true,
                    'choices' => [
                        'Artiste Solo' => 'solo',
                        'Groupe' => 'groupe',
                    ],
                    'placeholder' => 'Choisir le type',
                ])
                ->add('genresMusicaux', ModelType::class, [
                    'class' => 'App\Entity\Play\GenreMusical',
                    'multiple' => true,
                    'required' => true,
                    'btn_add' => true
                ])
                /* ->add('labell', ModelType::class, [
                  'class' => 'App\Entity\Play\Label',
                  'required' => true,
                  'btn_add' => true,
                  'placeholder' => 'Choisir le label',
                  ]) */
                ->end()
                ->with('Singles Audio', [
                    'class' => 'col-md-4',
                    'box_class' => 'box box-solid box-info',
                ])
                ->add('dateNais')
                ->end()
                ->with("Singles Vidéos", [
                    'class' => 'col-md-4',
                    'box_class' => 'box box-solid box-info',
                ])
                ->add('dateNais')
                ->end()
                ->with('Album Vidéos', [
                    'class' => 'col-md-4',
                    'box_class' => 'box box-solid box-info',
                ])
                ->add('dateNais')
                ->end()
                ->end()
                ->tab('Cinéma')
                ->add('dateNais')
                ->end()
                ->end()
                ->tab('Statistiques')
                ->add('dateNais')
                ->end()
                ->end()

        ;
    }

    protected function configureShowFields(ShowMapper $showMapper): void {
        $showMapper
                ->add('id')
                ->add('nom')
                ->add('username')
                ->add('email')
                ->add('password')
                ->add('roles')
                ->add('dateNais')
                ->add('sexe')
                ->add('telephone')
                ->add('anneeDeces')
                ->add('annee80')
                ->add('vues')
                ->add('description')
                ->add('type')

        ;
    }

}
