<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Gd\Encoders;

use Kanhaiyanigam05\Image\EncodedImage;
use Kanhaiyanigam05\Image\Encoders\AvifEncoder as GenericAvifEncoder;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;

class AvifEncoder extends GenericAvifEncoder implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see EncoderInterface::encode()
     */
    public function encode(ImageInterface $image): EncodedImage
    {
        return $this->createEncodedImage(function ($pointer) use ($image): void {
            imageavif($image->core()->native(), $pointer, $this->quality);
        }, 'image/avif');
    }
}
