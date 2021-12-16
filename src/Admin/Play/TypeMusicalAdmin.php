<?php

declare(strict_types=1);

namespace App\Admin\Play;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

final class TypeMusicalAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'play/type_musical';
    protected $baseRouteName = 'play_type_musical';

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('nom')
            ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
             ->addIdentifier('nom', NULL, ['editable' => true]);
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->add('nom')
            ;
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add('nom')
            ;
    }
}
