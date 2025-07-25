<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Imagick\Decoders;

use Imagick;
use ImagickException;
use Kanhaiyanigam05\Image\Exceptions\DecoderException;
use Kanhaiyanigam05\Image\Interfaces\ColorInterface;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;

class FilePathImageDecoder extends NativeObjectDecoder
{
    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::decode()
     */
    public function decode(mixed $input): ImageInterface|ColorInterface
    {
        if (!$this->isFile($input)) {
            throw new DecoderException('Unable to decode input');
        }

        try {
            $imagick = new Imagick();
            $imagick->readImage($input);
        } catch (ImagickException) {
            throw new DecoderException('Unable to decode input');
        }

        // decode image
        $image = parent::decode($imagick);

        // set file path on origin
        $image->origin()->setFilePath($input);

        // extract exif data for the appropriate formats
        if (in_array($imagick->getImageFormat(), ['JPEG', 'TIFF', 'TIF'])) {
            $image->setExif($this->extractExifData($input));
        }

        return $image;
    }
}
