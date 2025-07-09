<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Gd\Encoders;

use GdImage;
use Kanhaiyanigam05\Image\Drivers\Gd\Cloner;
use Kanhaiyanigam05\Image\EncodedImage;
use Kanhaiyanigam05\Image\Encoders\PngEncoder as GenericPngEncoder;
use Kanhaiyanigam05\Image\Exceptions\AnimationException;
use Kanhaiyanigam05\Image\Exceptions\ColorException;
use Kanhaiyanigam05\Image\Exceptions\RuntimeException;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;

class PngEncoder extends GenericPngEncoder implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see EncoderInterface::encode()
     */
    public function encode(ImageInterface $image): EncodedImage
    {
        $output = $this->prepareOutput($image);

        return $this->createEncodedImage(function ($pointer) use ($output): void {
            imageinterlace($output, $this->interlaced);
            imagepng($output, $pointer, -1);
        }, 'image/png');
    }

    /**
     * Prepare given image instance for PNG format output according to encoder settings
     *
     * @throws RuntimeException
     * @throws ColorException
     * @throws AnimationException
     */
    private function prepareOutput(ImageInterface $image): GdImage
    {
        if ($this->indexed) {
            $output = clone $image;
            $output->reduceColors(255);

            return $output->core()->native();
        }

        return Cloner::clone($image->core()->native());
    }
}
