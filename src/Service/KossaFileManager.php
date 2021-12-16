<?php

namespace App\Service;

use App\Application\Sonata\MediaBundle\Entity\Media;
use falahati\PHPMP3\MpegAudio;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Description of KossaFileManager
 *
 * @author mekomou
 */
class KossaFileManager  {
     protected $rootdir;
     
      public function __construct($rootdir) {
        $this->rootdir = $rootdir;
    }

    //les 30 permieres secondes
     public function getExtraitMusique(File $file,$context)
    {
       $filedirectory =  $this->rootdir . '/public/tmp';
        $extraitName = md5(uniqid()) . '.mp3';//. $file->guessExtension();
        $extraitPath = $filedirectory . '/' . $extraitName;
        MpegAudio::fromFile($file->getRealPath())->trim(0, 30)->saveFile($extraitPath);
        $extraitFile = new Media();
        $extraitFile->setBinaryContent(new File($extraitPath));
        $extraitFile->setContext($context);
        $extraitFile->setProviderName('sonata.media.provider.file');
      
        return $extraitFile;
    }
    
     //modifier les metadonnees d'un morceau
     public function getMusicId3(UploadedFile $albumArt,$titre,$artiste,$album,$noPiste,$genre,$commentaire,$annee)
    {
      
    }

}
