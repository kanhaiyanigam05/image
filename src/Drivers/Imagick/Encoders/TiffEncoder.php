<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Imagick\Encoders;

use Kanhaiyanigam05\Image\Drivers\Imagick\Modifiers\StripMetaModifier;
use Kanhaiyanigam05\Image\EncodedImage;
use Kanhaiyanigam05\Image\Encoders\TiffEncoder as GenericTiffEncoder;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\EncodedImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;

class TiffEncoder extends GenericTiffEncoder implements SpecializedInterface
{
    public function encode(ImageInterface $image): EncodedImageInterface
    {
        $format = 'TIFF';

        // strip meta data
        if ($this->strip || (is_null($this->strip) && $this->driver()->config()->strip)) {
            $image->modify(new StripMetaModifier());
        }

        $imagick = $image->core()->native();
        $imagick->setFormat($format);
        $imagick->setImageFormat($format);
        $imagick->setCompression($imagick->getImageCompression());
        $imagick->setImageCompression($imagick->getImageCompression());
        $imagick->setCompressionQuality($this->quality);
        $imagick->setImageCompressionQuality($this->quality);

        return new EncodedImage($imagick->getImagesBlob(), 'image/tiff');
    }
}
