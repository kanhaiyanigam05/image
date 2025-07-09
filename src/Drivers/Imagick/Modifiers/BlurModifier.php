<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Imagick\Modifiers;

use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;
use Kanhaiyanigam05\Image\Modifiers\BlurModifier as GenericBlurModifier;

class BlurModifier extends GenericBlurModifier implements SpecializedInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            $frame->native()->blurImage($this->amount, 0.5 * $this->amount);
        }

        return $image;
    }
}
