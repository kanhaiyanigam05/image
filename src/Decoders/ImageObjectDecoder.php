<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Decoders;

use Kanhaiyanigam05\Image\Drivers\AbstractDecoder;
use Kanhaiyanigam05\Image\Exceptions\DecoderException;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\ColorInterface;

class ImageObjectDecoder extends AbstractDecoder
{
    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::decode()
     */
    public function decode(mixed $input): ImageInterface|ColorInterface
    {
        if (!is_a($input, ImageInterface::class)) {
            throw new DecoderException('Unable to decode input');
        }

        return $input;
    }
}
