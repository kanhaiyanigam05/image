<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Imagick\Encoders;

use Imagick;
use ImagickPixel;
use Kanhaiyanigam05\Image\Drivers\Imagick\Modifiers\StripMetaModifier;
use Kanhaiyanigam05\Image\EncodedImage;
use Kanhaiyanigam05\Image\Encoders\WebpEncoder as GenericWebpEncoder;
use Kanhaiyanigam05\Image\Interfaces\EncodedImageInterface;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;

class WebpEncoder extends GenericWebpEncoder implements SpecializedInterface
{
    public function encode(ImageInterface $image): EncodedImageInterface
    {
        $format = 'WEBP';
        $compression = Imagick::COMPRESSION_ZIP;

        // strip meta data
        if ($this->strip || (is_null($this->strip) && $this->driver()->config()->strip)) {
            $image->modify(new StripMetaModifier());
        }

        $imagick = $image->core()->native();
        $imagick->setImageBackgroundColor(new ImagickPixel('transparent'));

        if (!$image->isAnimated()) {
            $imagick = $imagick->mergeImageLayers(Imagick::LAYERMETHOD_MERGE);
        }

        $imagick->setFormat($format);
        $imagick->setImageFormat($format);
        $imagick->setCompression($compression);
        $imagick->setImageCompression($compression);
        $imagick->setImageCompressionQuality($this->quality);

        if ($this->quality === 100) {
            $imagick->setOption('webp:lossless', 'true');
        }

        return new EncodedImage($imagick->getImagesBlob(), 'image/webp');
    }
}
