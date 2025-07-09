<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Gd\Modifiers;

use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;
use Kanhaiyanigam05\Image\Modifiers\DrawPixelModifier as GenericDrawPixelModifier;

class DrawPixelModifier extends GenericDrawPixelModifier implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $color = $this->driver()->colorProcessor($image->colorspace())->colorToNative(
            $this->driver()->handleInput($this->color)
        );

        foreach ($image as $frame) {
            imagealphablending($frame->native(), true);
            imagesetpixel(
                $frame->native(),
                $this->position->x(),
                $this->position->y(),
                $color
            );
        }

        return $image;
    }
}
