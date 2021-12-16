<?php

namespace App\EventSubscriber;

use App\Application\Sonata\MediaBundle\Entity\Media;
use App\Entity\Home\User;
use App\Utils\Slugger;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class AccountSubscriber
 * @package App\AccountSubscriber
 */
class AccountSubscriber implements EventSubscriber {

    /**
     * @return array|string[]
     */
    private $container;
    private $passwordEncoder;

    public function __construct(Container $container, UserPasswordEncoderInterface $encoder) {
        $this->container = $container;
        $this->passwordEncoder = $encoder;
    }

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
        if (!$entity instanceof User) {
            return;
        }
        if ($entity->getPlainPassword()) {
            $encodedPassword = $this->passwordEncoder->encodePassword($entity, $entity->getPlainPassword());
            $entity->setPassword($encodedPassword);
            $entity->setPlainPassword(null);
        }
        if ($entity->getUsername() == null) {
            $entity->setUsername($entity->getEmail());
        }
    }

    /**
     * @param PreUpdateEventArgs $event
     */
    public function preUpdate(PreUpdateEventArgs $event) {
        $entity = $event->getObject();

        if (!$entity instanceof User) {
            return;
        }
        if ($entity->getPlainPassword()) {
            $encodedPassword = $this->passwordEncoder->encodePassword($entity, $entity->getPlainPassword());
            $entity->setPassword($encodedPassword);
            $entity->setPlainPassword(null);
        }
        if ($entity->getUsername() == null) {
            $entity->setUsername($entity->getEmail());
        }
    }

}
