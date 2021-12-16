<?php

namespace App\EventSubscriber;

use App\Application\Sonata\MediaBundle\Entity\Media;
use App\Entity\Play\Single;
use App\Utils\Slugger;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

/**
 * Class SingleSluggerSubscriber
 * @package App\EventSubscriber
 */
class SingleSubscriber implements EventSubscriber {

    /**
     * @return array|string[]
     */
    private $container;

    public function __construct(Container $container) {
        $this->container = $container;
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
        if (!$entity instanceof Single) {
            return;
        }
        $this->update($args);
    }

    /**
     * @param PreUpdateEventArgs $event
     */
    public function preUpdate(PreUpdateEventArgs $event) {
        $entity = $event->getObject();

        if (!$entity instanceof Single) {
            return;
        }
        $this->update($event);
    }

    /**
     * @param  $event
     */
    private function update($event) {
        $single = $event->getObject();
        $audiofile = $single->getFichier();
        $file = $audiofile->getBinaryContent();

        //on modifie lesmeta donnees
        //$eyed3 = new EyeD3($file->getRealPath());
        /*$eyed3 = new EyeD3("/home/mekomou/Symfony Projects/kossa/public/tmp/ok.mp3");
        $tags = $eyed3->readMeta();
        $meta = [
            "artist" => "MyArtist",
            "title" => "MyTitle",
            "album" => "MyAlbum",
            "comment" => "MyComment",
            "lyrics" => "MyLyrics",
            "track" => 1,
                //"album_art" => "cover.png"
        ];
        // Update the mp3 file with the new meta tags
        $eyed3->updateMeta($meta);*/

        $mediaFile = new Media();
        $mediaFile->setBinaryContent($file);
        $mediaFile->setContext('play_single_fichier');
        $mediaFile->setProviderName('sonata.media.provider.file');
        $extraitFile = $this->container->get('kossa.file.manager')->getExtraitMusique($file, 'play_single_extrait');

        $single->setFichier($mediaFile);
        $single->setExtrait($extraitFile);

        //on va gerer les featuring apres
        $single->setNom($single->getArtiste()->getNom() . " - " . $single->getTitre() . " [www.kossa.cm]");
    }

}
