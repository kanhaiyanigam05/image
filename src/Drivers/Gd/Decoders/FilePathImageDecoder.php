<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Gd\Decoders;

use Kanhaiyanigam05\Image\Exceptions\DecoderException;
use Kanhaiyanigam05\Image\Format;
use Kanhaiyanigam05\Image\Interfaces\ColorInterface;
use Kanhaiyanigam05\Image\Interfaces\DecoderInterface;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Modifiers\AlignRotationModifier;

class FilePathImageDecoder extends NativeObjectDecoder implements DecoderInterface
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

        // detect media (mime) type
        $mediaType = $this->getMediaTypeByFilePath($input);

        $image = match ($mediaType->format()) {
            // gif files might be animated and therefore cannot
            // be handled by the standard GD decoder.
            Format::GIF => $this->decodeGif($input),
            default => parent::decode(match ($mediaType->format()) {
                Format::JPEG => @imagecreatefromjpeg($input),
                Format::WEBP => @imagecreatefromwebp($input),
                Format::PNG => @imagecreatefrompng($input),
                Format::AVIF => @imagecreatefromavif($input),
                Format::BMP => @imagecreatefrombmp($input),
                default => throw new DecoderException('Unable to decode input'),
            }),
        };

        // set file path & mediaType on origin
        $image->origin()->setFilePath($input);
        $image->origin()->setMediaType($mediaType);

        // extract exif for the appropriate formats
        if ($mediaType->format() === Format::JPEG) {
            $image->setExif($this->extractExifData($input));
        }

        // adjust image orientation
        if ($this->driver()->config()->autoOrientation) {
            $image->modify(new AlignRotationModifier());
        }

        return $image;
    }
}
