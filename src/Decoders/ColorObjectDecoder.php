<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Decoders;

use Kanhaiyanigam05\Image\Drivers\AbstractDecoder;
use Kanhaiyanigam05\Image\Exceptions\DecoderException;
use Kanhaiyanigam05\Image\Interfaces\ColorInterface;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;

class ColorObjectDecoder extends AbstractDecoder
{
    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::decode()
     */
    public function decode(mixed $input): ImageInterface|ColorInterface
    {
        if (!is_a($input, ColorInterface::class)) {
            throw new DecoderException('Unable to decode input');
        }

        return $input;
    }
}
