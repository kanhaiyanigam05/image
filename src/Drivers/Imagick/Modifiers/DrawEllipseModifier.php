<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Imagick\Modifiers;

use ImagickDraw;
use RuntimeException;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;
use Kanhaiyanigam05\Image\Modifiers\DrawEllipseModifier as GenericDrawEllipseModifier;

class DrawEllipseModifier extends GenericDrawEllipseModifier implements SpecializedInterface
{
    /**
     * @throws RuntimeException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $background_color = $this->driver()->colorProcessor($image->colorspace())->colorToNative(
            $this->backgroundColor()
        );

        $border_color = $this->driver()->colorProcessor($image->colorspace())->colorToNative(
            $this->borderColor()
        );

        foreach ($image as $frame) {
            $drawing = new ImagickDraw();
            $drawing->setFillColor($background_color);

            if ($this->drawable->hasBorder()) {
                $drawing->setStrokeWidth($this->drawable->borderSize());
                $drawing->setStrokeColor($border_color);
            }

            $drawing->ellipse(
                $this->drawable->position()->x(),
                $this->drawable->position()->y(),
                $this->drawable->width() / 2,
                $this->drawable->height() / 2,
                0,
                360
            );

            $frame->native()->drawImage($drawing);
        }

        return $image;
    }
}
