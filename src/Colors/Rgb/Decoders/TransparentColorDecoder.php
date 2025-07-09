<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Colors\Rgb\Decoders;

use Kanhaiyanigam05\Image\Exceptions\DecoderException;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\ColorInterface;

class TransparentColorDecoder extends HexColorDecoder
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

        if (strtolower($input) !== 'transparent') {
            throw new DecoderException('Unable to decode input');
        }

        return parent::decode('#ffffff00');
    }
}
