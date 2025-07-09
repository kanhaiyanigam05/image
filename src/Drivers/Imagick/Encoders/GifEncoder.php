<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Imagick\Encoders;

use Imagick;
use Kanhaiyanigam05\Image\EncodedImage;
use Kanhaiyanigam05\Image\Encoders\GifEncoder as GenericGifEncoder;
use Kanhaiyanigam05\Image\Interfaces\EncodedImageInterface;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;

class GifEncoder extends GenericGifEncoder implements SpecializedInterface
{
    public function encode(ImageInterface $image): EncodedImageInterface
    {
        $format = 'GIF';
        $compression = Imagick::COMPRESSION_LZW;

        $imagick = $image->core()->native();

        $imagick->setFormat($format);
        $imagick->setImageFormat($format);
        $imagick->setCompression($compression);
        $imagick->setImageCompression($compression);

        if ($this->interlaced) {
            $imagick->setInterlaceScheme(Imagick::INTERLACE_LINE);
        }

        return new EncodedImage($imagick->getImagesBlob(), 'image/gif');
    }
}
