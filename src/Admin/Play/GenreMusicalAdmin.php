<?php

declare(strict_types = 1);

namespace App\Admin\Play;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\MediaBundle\Form\Type\MediaType;

final class GenreMusicalAdmin extends AbstractAdmin {

    protected $baseRoutePattern = 'play/genre_musical';
    protected $baseRouteName = 'play_genre_musical';

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void {
        $datagridMapper
                ->add('nom')
                ->add('type')
        ;
    }

    protected function configureListFields(ListMapper $listMapper): void {
        $listMapper
                ->addIdentifier('nom', NULL, ['editable' => true])
                ->add('type')
        ;
    }

    protected function configureFormFields(FormMapper $formMapper): void {
        $formMapper
               ->add('type', ModelType::class, [
                    'required' => true,
                     'placeholder' => 'Choisir le type',
                    'btn_add' => true])
                ->add('nom')
                 ->add('description', CKEditorType::class, [
                    'required' => true,
                     'config_name' => 'genre_config'])
                 ->add('photo', MediaType::class, array(
                    'provider' => 'sonata.media.provider.image',
                    'context' => 'play_genremusical_photo',
                    'required' => false,
                    'label' => "Photo d'illustration",
                ))

        ;
    }

    protected function configureShowFields(ShowMapper $showMapper): void {
        $showMapper
                ->add('id')
                ->add('nom')
                ->add('description')
                ->add('type')
                ->add('vues')
        ;
    }

}
