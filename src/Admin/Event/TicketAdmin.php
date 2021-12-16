<?php

declare(strict_types=1);

namespace App\Admin\Event;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

final class TicketAdmin extends AbstractAdmin
{

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('id')
            ->add('type')
            ->add('prix')
            ->add('quantite')
            ->add('reste')
            ->add('active')
            ->add('date')
            ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('id')
            ->add('type')
            ->add('prix')
            ->add('quantite')
            ->add('reste')
            ->add('active')
            ->add('date')
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->add('id')
            ->add('type')
            ->add('prix')
            ->add('quantite')
            ->add('reste')
            ->add('active')
            ->add('date')
            ;
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add('id')
            ->add('type')
            ->add('prix')
            ->add('quantite')
            ->add('reste')
            ->add('active')
            ->add('date')
            ;
    }
}
