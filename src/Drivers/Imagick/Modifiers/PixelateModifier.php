<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Imagick\Modifiers;

use Kanhaiyanigam05\Image\Interfaces\FrameInterface;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;
use Kanhaiyanigam05\Image\Modifiers\PixelateModifier as GenericPixelateModifier;

class PixelateModifier extends GenericPixelateModifier implements SpecializedInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            $this->pixelateFrame($frame);
        }

        return $image;
    }

    protected function pixelateFrame(FrameInterface $frame): void
    {
        $size = $frame->size();

        $frame->native()->scaleImage(
            (int) round(max(1, $size->width() / $this->size)),
            (int) round(max(1, $size->height() / $this->size))
        );

        $frame->native()->scaleImage($size->width(), $size->height());
    }
}
