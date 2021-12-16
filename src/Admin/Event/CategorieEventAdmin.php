<?php

declare(strict_types=1);

namespace App\Admin\Event;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

final class CategorieEventAdmin extends AbstractAdmin
{

    protected $baseRoutePattern = 'event/categorie';
    protected $baseRouteName = 'event_categorie';

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('nom')
            ->add('color')
            ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
           ->addIdentifier('nom', NULL, ['editable' => true])
            ->add('color');
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->add('nom')
            ->add('color')
            ;
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add('id')
            ->add('nom')
            ->add('color')
            ;
    }
}
