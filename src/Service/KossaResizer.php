<?php

namespace App\Service;

use Gaufrette\File;
use Imagine\Exception\InvalidArgumentException;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Image\ImagineInterface;
use Imagine\Image\Point;
use Sonata\MediaBundle\Metadata\MetadataBuilderInterface;
use Sonata\MediaBundle\Model\MediaInterface;
use Sonata\MediaBundle\Resizer\ResizerInterface;

/**
 * Description of KossaResizer
 *
 * @author mekomou
 */
class KossaResizer implements ResizerInterface {

    /**
     * @var ImagineInterface
     */
    protected $adapter;

    /**
     * @var string
     */
    protected $mode;

    /**
     * @var MetadataBuilderInterface
     */
    protected $metadata;
    
    protected $rootdir;

    /**
     * @param ImagineInterface         $adapter
     * @param string                   $mode
     * @param MetadataBuilderInterface $metadata
     */
    public function __construct(ImagineInterface $adapter, $mode, MetadataBuilderInterface $metadata,$rootdir) {
        $this->adapter = $adapter;
        $this->mode = $mode;
        $this->metadata = $metadata;
        $this->rootdir = $rootdir;
    }

    /**
     * {@inheritdoc}
     */
    public function resize(MediaInterface $media, File $in, File $out, $format, array $settings) {
        if (!isset($settings['width'])) {
            throw new \RuntimeException(sprintf('Width parameter is missing in context "%s" for provider "%s"', $media->getContext(), $media->getProviderName()));
        }

        $imagine = new Imagine();  
        $webPath = $this->rootdir . '/public/watermark.png'; 
       $image = $this->adapter->load($in->getContent());
        $watermark = $imagine->open($webPath);
        $watermark->resize(new Box(25, 25));

        $size = $image->getSize();
        $wSize = $watermark->getSize();

        $bottomRight = new Point($size->getWidth() - $wSize->getWidth(), $size->getHeight() - $wSize->getHeight());

        $image->paste($watermark, $bottomRight);

        $content = $image
                ->thumbnail($this->getBox($media, $settings), $this->mode)
                ->get($format, ['quality' => $settings['quality']]);

        $out->setContent($content, $this->metadata->get($media, $out->getName()));
    }

    /**
     * {@inheritdoc}
     */
    public function getBox(MediaInterface $media, array $settings) {
        $size = $media->getBox();

        if (null === $settings['width'] && null === $settings['height']) {
            throw new \RuntimeException(sprintf('Width/Height parameter is missing in context "%s" for provider "%s". Please add at least one parameter.', $media->getContext(), $media->getProviderName()));
        }

        if (null === $settings['height']) {
            $settings['height'] = (int) round($settings['width'] * $size->getHeight() / $size->getWidth());
        }

        if (null === $settings['width']) {
            $settings['width'] = (int) round($settings['height'] * $size->getWidth() / $size->getHeight());
        }

        return $this->computeBox($media, $settings);
    }

    /**
     * @param MediaInterface $media
     * @param array          $settings
     *
     * @throws InvalidArgumentException
     *
     * @return Box
     */
    protected function computeBox(MediaInterface $media, array $settings) {
        if (ImageInterface::THUMBNAIL_INSET !== $this->mode && ImageInterface::THUMBNAIL_OUTBOUND !== $this->mode) {
            throw new InvalidArgumentException('Invalid mode specified');
        }

        $size = $media->getBox();

        $ratios = [
            $settings['width'] / $size->getWidth(),
            $settings['height'] / $size->getHeight(),
        ];

        if (ImageInterface::THUMBNAIL_INSET === $this->mode) {
            $ratio = min($ratios);
        } else {
            $ratio = max($ratios);
        }

        $scaledBox = $size->scale($ratio);

        return new Box(
                min($scaledBox->getWidth(), $settings['width']), min($scaledBox->getHeight(), $settings['height'])
        );
    }

}
