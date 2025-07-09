<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Imagick\Decoders;

use Imagick;
use ImagickException;
use Kanhaiyanigam05\Image\Exceptions\DecoderException;
use Kanhaiyanigam05\Image\Format;
use Kanhaiyanigam05\Image\Interfaces\ColorInterface;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;

class BinaryImageDecoder extends NativeObjectDecoder
{
    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::decode()
     */
    public function decode(mixed $input): ImageInterface|ColorInterface
    {
        if (!is_string($input)) {
            throw new DecoderException('Unable to decode input');
        }

        try {
            $imagick = new Imagick();
            $imagick->readImageBlob($input);
        } catch (ImagickException) {
            throw new DecoderException('Unable to decode input');
        }

        // decode image
        $image = parent::decode($imagick);

        // get media type enum from string media type
        $format = Format::tryCreate($image->origin()->mediaType());

        // extract exif data for appropriate formats
        if (in_array($format, [Format::JPEG, Format::TIFF])) {
            $image->setExif($this->extractExifData($input));
        }

        return $image;
    }
}
