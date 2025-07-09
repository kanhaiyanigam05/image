<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Encoders;

use Kanhaiyanigam05\Image\Interfaces\EncodedImageInterface;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;

class AutoEncoder extends MediaTypeEncoder
{
    /**
     * {@inheritdoc}
     *
     * @see EncoderInterface::encode()
     */
    public function encode(ImageInterface $image): EncodedImageInterface
    {
        return $image->encode(
            $this->encoderByMediaType(
                $image->origin()->mediaType()
            )
        );
    }
}
