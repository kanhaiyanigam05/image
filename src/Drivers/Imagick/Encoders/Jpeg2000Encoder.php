<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Imagick\Encoders;

use Imagick;
use Kanhaiyanigam05\Image\Drivers\Imagick\Modifiers\StripMetaModifier;
use Kanhaiyanigam05\Image\EncodedImage;
use Kanhaiyanigam05\Image\Encoders\Jpeg2000Encoder as GenericJpeg2000Encoder;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\EncodedImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;

class Jpeg2000Encoder extends GenericJpeg2000Encoder implements SpecializedInterface
{
    public function encode(ImageInterface $image): EncodedImageInterface
    {
        $format = 'JP2';
        $compression = Imagick::COMPRESSION_JPEG;

        // strip meta data
        if ($this->strip || (is_null($this->strip) && $this->driver()->config()->strip)) {
            $image->modify(new StripMetaModifier());
        }

        $imagick = $image->core()->native();
        $imagick->setImageBackgroundColor('white');
        $imagick->setBackgroundColor('white');
        $imagick->setFormat($format);
        $imagick->setImageFormat($format);
        $imagick->setCompression($compression);
        $imagick->setImageCompression($compression);
        $imagick->setCompressionQuality($this->quality);
        $imagick->setImageCompressionQuality($this->quality);

        return new EncodedImage($imagick->getImagesBlob(), 'image/jp2');
    }
}
