<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Imagick\Modifiers;

use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;
use Kanhaiyanigam05\Image\Modifiers\RotateModifier as GenericRotateModifier;

class RotateModifier extends GenericRotateModifier implements SpecializedInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $background = $this->driver()->colorProcessor($image->colorspace())->colorToNative(
            $this->driver()->handleInput($this->background)
        );

        foreach ($image as $frame) {
            $frame->native()->rotateImage(
                $background,
                $this->rotationAngle() * -1
            );
        }

        return $image;
    }
}
