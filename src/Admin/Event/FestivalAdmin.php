<?php

declare(strict_types=1);

namespace App\Admin\Event;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Form\Type\DateTimePickerType;
use Sonata\FormatterBundle\Form\Type\FormatterType;
use Sonata\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

final class FestivalAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'event/festival';
    protected $baseRouteName = 'event_festival';

      public function createQuery($context = 'list') {
        $query = parent::createQuery($context);
        $query->addSelect('c')
                ->innerJoin($query->getRootAliases()[0] . '.categorieEvent', 'c');
        $query->andWhere(
                $query->expr()->eq('c.nom', ':my_param')
        );
        $query->setParameter('my_param', 'festival');
        return $query;
    }

  
    
       protected function configureDatagridFilters(DatagridMapper $datagridMapper): void {
        $datagridMapper
                ->add('publishedAt')
                ->add('vues')
                ->add('jaime')
                ->add('jaimepas')
                ->add('dateRedaction')
                ->add('titre')
                ->add('dateDebut')
        ;
    }

    protected function configureListFields(ListMapper $listMapper): void {
        $listMapper
                ->addIdentifier('titre', NULL, ['editable' => true])
                ->add('author', NULL, array(
                    'admin_code' => 'admin.home.user_admin'
                ))
                ->add('publishedAt')
                ->add('vues')
                ->add('jaime')
                ->add('jaimepas')
                ->add('dateRedaction')
                ->add('dateDebut')
                ->add('dateFin');
    }

    protected function configureFormFields(FormMapper $formMapper): void {
        $formMapper
                ->tab('Infos sur le festival')
                ->add('author', NULL, [], ['admin_code' => 'admin.home.user_admin'])
                ->add('titre')
                ->add('photo', MediaType::class, array(
                    'provider' => 'sonata.media.provider.image',
                    'context' => 'event_festival_photo',
                    'required' => true,
                    'label' => "Photo du festival",
                ))
                 ->add('rawContent', FormatterType::class, [
                    'source_field' => 'rawContent',
                    'source_field_options' => ['attr' => ['class' => 'span10', 'rows' => 20]],
                    'format_field' => 'contentFormatter',
                    'target_field' => 'content',
                    'ckeditor_context' => 'default',
                    'event_dispatcher' => $formMapper->getFormBuilder()->getEventDispatcher(),
                ])
                ->add('content',null,array(
                    'required' => false,
               ))
                ->add('tags', ModelType::class, [
                    'class' => 'App\Entity\Home\Tag',
                    'required' => true,
                    'multiple' => true,
                    'btn_add' => true,
                    'placeholder' => 'Ajoutez des mots cl??s'])
                ->add('publishedAt')
                ->add('dateDebut')
                ->add('dateFin')
                 ->add('publie')
                ->end()
                ->end()
                ->tab('Tickets du festival')
                ->add('tickets', CollectionType::class, [
                    'entry_type' => 'App\Form\TicketType',
                    'by_reference' => false,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'required' => false
                ])
                ->end()
        ;
    }

    protected function configureShowFields(ShowMapper $showMapper): void {
        $showMapper
                ->add('id')
                ->add('content')
                ->add('publishedAt')
                ->add('vues')
                ->add('jaime')
                ->add('jaimepas')
                ->add('dateRedaction')
                ->add('titre')
                ->add('dateDebut')
                ->add('dateFin')
                ->add('dateFinAward')
        ;
    }

    public function prePersist($object) {
        $em = $this->getConfigurationPool()->getContainer()->get('doctrine')->getManager();
        $categorie = $em->getRepository('App\Entity\Event\CategorieEvent')->findOneBy(array('nom' => 'festival'));
        $object->setCategorieEvent($categorie);
    }

}
