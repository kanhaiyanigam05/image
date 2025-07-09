<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Gd\Modifiers;

use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;
use Kanhaiyanigam05\Image\Modifiers\GreyscaleModifier as GenericGreyscaleModifier;

class GreyscaleModifier extends GenericGreyscaleModifier implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            imagefilter($frame->native(), IMG_FILTER_GRAYSCALE);
        }

        return $image;
    }
}
