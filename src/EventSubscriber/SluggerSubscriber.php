<?php

namespace App\EventSubscriber;

use App\Entity\News\Article;
use App\Entity\Play\GenreMusical;
use App\Entity\Event\Event;
use App\Utils\Slugger;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

/**
 * Class SluggerSubscriber
 * @package App\EventSubscriber
 */
class SluggerSubscriber implements EventSubscriber {

    /**
     * @return array|string[]
     */
    public function getSubscribedEvents() {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args) {
        $entity = $args->getObject();

        if ($entity instanceof Article) {
            $entity->setSlug(Slugger::slugify($entity->getTitle()));
        }

        if ($entity instanceof GenreMusical) {
            $entity->setSlug(Slugger::slugify($entity->getNom()));
        }
        
        if ($entity instanceof Event) {
            $entity->setSlug(Slugger::slugify($entity->getTitre()));
        }
    }

    /**
     * @param PreUpdateEventArgs $event
     */
    public function preUpdate(PreUpdateEventArgs $event) {
        $entity = $event->getObject();

        if ($entity instanceof Article) {
            if ($event->hasChangedField('title')) {
                $entity->setSlug(Slugger::slugify($event->getNewValue('title')));
            }
        }

        if ($entity instanceof GenreMusical) {
            if ($event->hasChangedField('nom')) {
                $entity->setSlug(Slugger::slugify($event->getNewValue('nom')));
            }
        }
        
        if ($entity instanceof Event) {
            if ($event->hasChangedField('titre')) {
                $entity->setSlug(Slugger::slugify($event->getNewValue('titre')));
            }
        }
    }

}
