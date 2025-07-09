<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Gd\Encoders;

use Kanhaiyanigam05\Image\EncodedImage;
use Kanhaiyanigam05\Image\Encoders\WebpEncoder as GenericWebpEncoder;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;

class WebpEncoder extends GenericWebpEncoder implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see EncoderInterface::encode()
     */
    public function encode(ImageInterface $image): EncodedImage
    {
        $quality = $this->quality === 100 && defined('IMG_WEBP_LOSSLESS') ? IMG_WEBP_LOSSLESS : $this->quality;

        return $this->createEncodedImage(function ($pointer) use ($image, $quality): void {
            imagewebp($image->core()->native(), $pointer, $quality);
        }, 'image/webp');
    }
}
