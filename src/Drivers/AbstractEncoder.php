<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers;

use Kanhaiyanigam05\Image\EncodedImage;
use Kanhaiyanigam05\Image\Exceptions\RuntimeException;
use Kanhaiyanigam05\Image\Interfaces\EncodedImageInterface;
use Kanhaiyanigam05\Image\Interfaces\EncoderInterface;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Traits\CanBuildFilePointer;

abstract class AbstractEncoder implements EncoderInterface
{
    use CanBuildFilePointer;

    public const DEFAULT_QUALITY = 75;

    /**
     * {@inheritdoc}
     *
     * @see EncoderInterface::encode()
     */
    public function encode(ImageInterface $image): EncodedImageInterface
    {
        return $image->encode($this);
    }

    /**
     * Build new file pointer, run callback with it and return result as encoded image
     *
     * @throws RuntimeException
     */
    protected function createEncodedImage(callable $callback, ?string $mediaType = null): EncodedImage
    {
        $pointer = $this->buildFilePointer();
        $callback($pointer);

        return is_string($mediaType) ? new EncodedImage($pointer, $mediaType) : new EncodedImage($pointer);
    }
}
