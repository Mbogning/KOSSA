<?php

declare(strict_types = 1);

namespace App\Admin\Home;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

final class TagAdmin extends AbstractAdmin {

    protected $baseRoutePattern = 'home/tag';
    protected $baseRouteName = 'home_tag';

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void {
        $datagridMapper
                ->add('name')
        ;
    }

    protected function configureListFields(ListMapper $listMapper): void {
        $listMapper
                ->addIdentifier('name', NULL, ['editable' => true]);
    }

    protected function configureFormFields(FormMapper $formMapper): void {
        $formMapper
               ->add('name')
        ;
    }

    protected function configureShowFields(ShowMapper $showMapper): void {
        $showMapper
                ->add('name')
        ;
    }

}
