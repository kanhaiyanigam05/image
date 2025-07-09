<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Gd\Decoders;

use Kanhaiyanigam05\Image\Exceptions\DecoderException;
use Kanhaiyanigam05\Image\Interfaces\ColorInterface;
use Kanhaiyanigam05\Image\Interfaces\DecoderInterface;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;

class Base64ImageDecoder extends BinaryImageDecoder implements DecoderInterface
{
    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::decode()
     */
    public function decode(mixed $input): ImageInterface|ColorInterface
    {
        if (!$this->isValidBase64($input)) {
            throw new DecoderException('Unable to decode input');
        }

        return parent::decode(base64_decode((string) $input));
    }
}
