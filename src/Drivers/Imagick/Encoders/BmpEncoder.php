<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Imagick\Encoders;

use Imagick;
use Kanhaiyanigam05\Image\EncodedImage;
use Kanhaiyanigam05\Image\Encoders\BmpEncoder as GenericBmpEncoder;
use Kanhaiyanigam05\Image\Interfaces\EncodedImageInterface;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;

class BmpEncoder extends GenericBmpEncoder implements SpecializedInterface
{
    public function encode(ImageInterface $image): EncodedImageInterface
    {
        $format = 'BMP';
        $compression = Imagick::COMPRESSION_NO;

        $imagick = $image->core()->native();
        $imagick->setFormat($format);
        $imagick->setImageFormat($format);
        $imagick->setCompression($compression);
        $imagick->setImageCompression($compression);

        return new EncodedImage($imagick->getImagesBlob(), 'image/bmp');
    }
}
