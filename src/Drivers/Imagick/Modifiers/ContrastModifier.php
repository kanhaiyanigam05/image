<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Imagick\Modifiers;

use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;
use Kanhaiyanigam05\Image\Modifiers\ContrastModifier as GenericContrastModifier;

class ContrastModifier extends GenericContrastModifier implements SpecializedInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            $frame->native()->sigmoidalContrastImage($this->level > 0, abs($this->level / 4), 0);
        }

        return $image;
    }
}
