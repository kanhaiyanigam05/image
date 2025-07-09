<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Gd\Modifiers;

use RuntimeException;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;
use Kanhaiyanigam05\Image\Modifiers\DrawLineModifier as GenericDrawLineModifier;

class DrawLineModifier extends GenericDrawLineModifier implements SpecializedInterface
{
    /**
     * @throws RuntimeException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $color = $this->driver()->colorProcessor($image->colorspace())->colorToNative(
            $this->backgroundColor()
        );

        foreach ($image as $frame) {
            imagealphablending($frame->native(), true);
            imageantialias($frame->native(), true);
            imagesetthickness($frame->native(), $this->drawable->width());
            imageline(
                $frame->native(),
                $this->drawable->start()->x(),
                $this->drawable->start()->y(),
                $this->drawable->end()->x(),
                $this->drawable->end()->y(),
                $color
            );
        }

        return $image;
    }
}
