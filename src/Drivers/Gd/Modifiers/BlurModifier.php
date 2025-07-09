<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Gd\Modifiers;

use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;
use Kanhaiyanigam05\Image\Modifiers\BlurModifier as GenericBlurModifier;

class BlurModifier extends GenericBlurModifier implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            for ($i = 0; $i < $this->amount; $i++) {
                imagefilter($frame->native(), IMG_FILTER_GAUSSIAN_BLUR);
            }
        }

        return $image;
    }
}
