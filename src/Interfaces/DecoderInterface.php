<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Interfaces;

use Kanhaiyanigam05\Image\Exceptions\RuntimeException;

interface DecoderInterface
{
    /**
     * Decode given input either to color or image
     *
     * @throws RuntimeException
     */
    public function decode(mixed $input): ImageInterface|ColorInterface;
}
