<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Imagick\Modifiers;

use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;
use Kanhaiyanigam05\Image\Modifiers\FlipModifier as GenericFlipModifier;

class FlipModifier extends GenericFlipModifier implements SpecializedInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            $frame->native()->flipImage();
        }

        return $image;
    }
}
