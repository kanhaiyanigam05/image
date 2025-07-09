<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Gd\Decoders;

use Kanhaiyanigam05\Image\Exceptions\RuntimeException;
use Kanhaiyanigam05\Image\Interfaces\ColorInterface;
use Kanhaiyanigam05\Image\Interfaces\DecoderInterface;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Exceptions\DecoderException;
use Kanhaiyanigam05\Image\Format;
use Kanhaiyanigam05\Image\Modifiers\AlignRotationModifier;

class BinaryImageDecoder extends NativeObjectDecoder implements DecoderInterface
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

        return match ($this->isGifFormat($input)) {
            true => $this->decodeGif($input),
            default => $this->decodeBinary($input),
        };
    }

    /**
     * Decode image from given binary data
     *
     * @throws RuntimeException
     */
    private function decodeBinary(string $input): ImageInterface
    {
        $gd = @imagecreatefromstring($input);

        if ($gd === false) {
            throw new DecoderException('Unable to decode input');
        }

        // create image instance
        $image = parent::decode($gd);

        // get media type
        $mediaType = $this->getMediaTypeByBinary($input);

        // extract & set exif data for appropriate formats
        if (in_array($mediaType->format(), [Format::JPEG, Format::TIFF])) {
            $image->setExif($this->extractExifData($input));
        }

        // set mediaType on origin
        $image->origin()->setMediaType($mediaType);

        // adjust image orientation
        if ($this->driver()->config()->autoOrientation) {
            $image->modify(new AlignRotationModifier());
        }

        return $image;
    }
}
