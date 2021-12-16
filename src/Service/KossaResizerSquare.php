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
 * Description of KossaResizerSquare
 *
 * @author mekomou
 */
class KossaResizerSquare implements ResizerInterface {

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
    public function __construct(ImagineInterface $adapter, $mode, MetadataBuilderInterface $metadata, $rootdir) {
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

        $image = $this->adapter->load($in->getContent());
        $size = $media->getBox();

        if (null !== $settings['height']) {
            if ($size->getHeight() > $size->getWidth()) {
                $higher = $size->getHeight();
                $lower = $size->getWidth();
            } else {
                $higher = $size->getWidth();
                $lower = $size->getHeight();
            }

            $crop = $higher - $lower;

            if ($crop > 0) {
                $point = $higher === $size->getHeight() ? new Point(0, 0) : new Point($crop / 2, 0);
                $image->crop($point, new Box($lower, $lower));
                $size = $image->getSize();
            }
        }

        $settings['height'] = (int) ($settings['width'] * $size->getHeight() / $size->getWidth());


        $imagine = new Imagine();
        $webPath = $this->rootdir . '/public/watermark.png';
        $image = $this->adapter->load($in->getContent());
        $watermark = $imagine->open($webPath);
        $watermark->resize(new Box(15, 25));

        $size = $image->getSize();
        $wSize = $watermark->getSize();

        $bottomRight = new Point($size->getWidth() - $wSize->getWidth(), $size->getHeight() - $wSize->getHeight());

        $image->paste($watermark, $bottomRight);


        if ($settings['height'] < $size->getHeight() && $settings['width'] < $size->getWidth()) {
            $content = $image
                    ->thumbnail(new Box($settings['width'], $settings['height']), $this->mode)
                    ->get($format, ['quality' => $settings['quality']]);
        } else {
            $content = $image->get($format, ['quality' => $settings['quality']]);
        }

        $out->setContent($content, $this->metadata->get($media, $out->getName()));
    }

    /**
     * {@inheritdoc}
     */
    public function getBox(MediaInterface $media, array $settings) {
        $size = $media->getBox();

        if (null !== $settings['height']) {
            if ($size->getHeight() > $size->getWidth()) {
                $higher = $size->getHeight();
                $lower = $size->getWidth();
            } else {
                $higher = $size->getWidth();
                $lower = $size->getHeight();
            }

            if ($higher - $lower > 0) {
                $size = new Box($lower, $lower);
            }
        }

        $settings['height'] = (int) ($settings['width'] * $size->getHeight() / $size->getWidth());

        if ($settings['height'] < $size->getHeight() && $settings['width'] < $size->getWidth()) {
            return new Box($settings['width'], $settings['height']);
        }

        return $size;
    }

}
