<?php

declare(strict_types = 1);

namespace App\Admin\News;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\FormatterBundle\Form\Type\FormatterType;
use Sonata\MediaBundle\Form\Type\MediaType;

final class ArticleAdmin extends AbstractAdmin {

    protected $baseRoutePattern = 'news/article';
    protected $baseRouteName = 'news_article';

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void {
        $datagridMapper
                ->add('title')
                ->add('slug')
                ->add('summary')
                ->add('content')
                ->add('publishedAt')
        ;
    }

    protected function configureListFields(ListMapper $listMapper): void {
        $listMapper
                ->addIdentifier('title', NULL, ['editable' => true])
                ->add('author', NULL, array(
                    'admin_code' => 'admin.home.user_admin'
                ))
                ->add('categorie')
                ->add('publishedAt')
                ->add('dateRedaction')
                ->add('vues')
                ->add('publie', NULL, ['editable' => true])
                ->add('hasVideo', NULL, ['editable' => true])
        ;
    }

    protected function configureFormFields(FormMapper $formMapper): void {
        $formMapper
                ->add('title')
                ->add('summary')
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
                ->add('publishedAt')
                ->add('author', NULL, [], ['admin_code' => 'admin.home.user_admin'])
                ->add('photo', MediaType::class, array(
                    'provider' => 'sonata.media.provider.image',
                    'context' => 'home_article_photo',
                    'required' => false,
                    'label' => "Photo d'illustraton",
                ))
                ->add('tags', ModelType::class, [
                    'class' => 'App\Entity\Home\Tag',
                    'required' => true,
                    'multiple' => true,
                    'btn_add' => true,
                    'placeholder' => 'Ajoutez des mots clés'])
                ->add('categorie', ModelType::class, [
                    'required' => true,
                    'btn_add' => true])
                ->add('publie', NULL, ['required' => false])
                ->add('hasVideo')
                ->add('hasPhoto')
                ->add('hasAudio')

        ;
    }

    protected function configureShowFields(ShowMapper $showMapper): void {
        $showMapper
                ->add('title')
                ->add('slug')
                ->add('summary')
                ->add('content')
                ->add('publishedAt')
        ;
    }
    

}
