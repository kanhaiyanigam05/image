<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Gd\Decoders;

use Kanhaiyanigam05\Image\EncodedImage;
use Kanhaiyanigam05\Image\Exceptions\DecoderException;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\ColorInterface;

class EncodedImageObjectDecoder extends BinaryImageDecoder
{
    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::decode()
     */
    public function decode(mixed $input): ImageInterface|ColorInterface
    {
        if (!is_a($input, EncodedImage::class)) {
            throw new DecoderException('Unable to decode input');
        }

        return parent::decode($input->toString());
    }
}
