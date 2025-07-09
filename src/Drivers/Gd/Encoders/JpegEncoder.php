<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Gd\Encoders;

use Kanhaiyanigam05\Image\Drivers\Gd\Cloner;
use Kanhaiyanigam05\Image\Encoders\JpegEncoder as GenericJpegEncoder;
use Kanhaiyanigam05\Image\EncodedImage;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;

class JpegEncoder extends GenericJpegEncoder implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see EncoderInterface::encode()
     */
    public function encode(ImageInterface $image): EncodedImage
    {
        $blendingColor = $this->driver()->handleInput(
            $this->driver()->config()->blendingColor
        );

        $output = Cloner::cloneBlended(
            $image->core()->native(),
            background: $blendingColor
        );

        return $this->createEncodedImage(function ($pointer) use ($output): void {
            imageinterlace($output, $this->progressive);
            imagejpeg($output, $pointer, $this->quality);
        }, 'image/jpeg');
    }
}
