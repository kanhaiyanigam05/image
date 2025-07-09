<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Imagick\Encoders;

use Kanhaiyanigam05\Image\Drivers\Imagick\Modifiers\StripMetaModifier;
use Kanhaiyanigam05\Image\EncodedImage;
use Kanhaiyanigam05\Image\Encoders\HeicEncoder as GenericHeicEncoder;
use Kanhaiyanigam05\Image\Interfaces\EncodedImageInterface;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;

class HeicEncoder extends GenericHeicEncoder implements SpecializedInterface
{
    public function encode(ImageInterface $image): EncodedImageInterface
    {
        $format = 'HEIC';

        // strip meta data
        if ($this->strip || (is_null($this->strip) && $this->driver()->config()->strip)) {
            $image->modify(new StripMetaModifier());
        }

        $imagick = $image->core()->native();
        $imagick->setFormat($format);
        $imagick->setImageFormat($format);
        $imagick->setCompressionQuality($this->quality);
        $imagick->setImageCompressionQuality($this->quality);

        return new EncodedImage($imagick->getImagesBlob(), 'image/heic');
    }
}
