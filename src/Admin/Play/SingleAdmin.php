<?php

declare(strict_types = 1);

namespace App\Admin\Play;

use App\Application\Sonata\MediaBundle\Entity\Media;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\MediaBundle\Form\Type\MediaType;
use Stormiix\EyeD3\EyeD3;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

final class SingleAdmin extends AbstractAdmin {

    protected $baseRoutePattern = 'play/single';
    protected $baseRouteName = 'play_single';

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void {
        $datagridMapper
                ->add('titre')
                ->add('noPiste')
                ->add('duree')
                ->add('quantite')
                ->add('type')
                ->add('prix')
                ->add('vues')
        ;
    }

    protected function configureListFields(ListMapper $listMapper): void {
        $listMapper
                ->addIdentifier('titre', NULL, ['editable' => true])
                ->add('fichier',null,array('label'=>'Musique','template'=>'kossa/play/admin/play_music.html.twig'))
                ->add('extrait',null,array('label'=>'Extrait','template'=>'kossa/play/admin/play_music_extrait.html.twig'))
                ->add('duree')
                ->add('artiste', NULL, array(
                    'admin_code' => 'admin.home.utilisateur'
        ));
    }

    protected function configureFormFields(FormMapper $formMapper): void {
        $formMapper
                ->with('Informations', [
                    'class' => 'col-md-4',
                    'box_class' => 'box box-solid box-primary',
                ])
                ->add('titre')
                ->add('type', ChoiceType::class, [
                    'required' => true,
                    'choices' => [
                        'Gratuit' => 'gratuit',
                        'Payant' => 'payant',
                    ],
                    'placeholder' => 'Choisir le type',
                ])
                ->add('artiste', ModelType::class, [
                    'class' => 'App\Entity\Home\User',
                    'required' => true,
                    'btn_add' => true,
                    'placeholder' => 'Choisir le musicien',
                        ], ['admin_code' => 'admin.home.utilisateur'])
                ->add('featuring', ModelType::class, [
                    'class' => 'App\Entity\Home\User',
                    'required' => true,
                    'multiple' => true,
                    'btn_add' => true,
                    'placeholder' => 'Choisir le musicien',
                        ], ['admin_code' => 'admin.home.utilisateur'])
                 ->add('quantite')
                
                ->end()
                ->with('Fichiers', [
                    'class' => 'col-md-8',
                    'box_class' => 'box box-solid box-info',
                ])
                ->add('annee')
                ->add('prix')
                ->add('photo', MediaType::class, array(
                    'provider' => 'sonata.media.provider.image',
                    'context' => 'play_single_photo',
                    'required' => false,
                    'label' => "Photo",
                ))
                ->add('fichier', MediaType::class, array(
                    'provider' => 'sonata.media.provider.file',
                    'context' => 'play_single_fichier',
                    'required' => true,
                ))
                ->end()

        ;
    }

    protected function configureShowFields(ShowMapper $showMapper): void {
        $showMapper
                ->add('id')
                ->add('titre')
                ->add('noPiste')
                ->add('duree')
                ->add('quantite')
                ->add('type')
                ->add('prix')
                ->add('vues')
        ;
    }
 
}
