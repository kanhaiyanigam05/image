<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Interfaces;

use Kanhaiyanigam05\Image\Exceptions\RuntimeException;

interface EncoderInterface
{
    /**
     * Encode given image
     *
     * @throws RuntimeException
     */
    public function encode(ImageInterface $image): EncodedImageInterface;
}
