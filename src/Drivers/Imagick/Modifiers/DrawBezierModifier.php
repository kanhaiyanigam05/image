<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Imagick\Modifiers;

use ImagickDraw;
use RuntimeException;
use Kanhaiyanigam05\Image\Exceptions\GeometryException;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;
use Kanhaiyanigam05\Image\Modifiers\DrawBezierModifier as GenericDrawBezierModifier;

class DrawBezierModifier extends GenericDrawBezierModifier implements SpecializedInterface
{
    /**
     * @throws RuntimeException
     * @throws GeometryException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        if ($this->drawable->count() !== 3 && $this->drawable->count() !== 4) {
            throw new GeometryException('You must specify either 3 or 4 points to create a bezier curve');
        }

        $drawing = new ImagickDraw();

        if ($this->drawable->hasBackgroundColor()) {
            $background_color = $this->driver()->colorProcessor($image->colorspace())->colorToNative(
                $this->backgroundColor()
            );
        } else {
            $background_color = 'transparent';
        }

        $drawing->setFillColor($background_color);

        if ($this->drawable->hasBorder() && $this->drawable->borderSize() > 0) {
            $border_color = $this->driver()->colorProcessor($image->colorspace())->colorToNative(
                $this->borderColor()
            );

            $drawing->setStrokeColor($border_color);
            $drawing->setStrokeWidth($this->drawable->borderSize());
        }

        $drawing->pathStart();
        $drawing->pathMoveToAbsolute(
            $this->drawable->first()->x(),
            $this->drawable->first()->y()
        );
        if ($this->drawable->count() === 3) {
            $drawing->pathCurveToQuadraticBezierAbsolute(
                $this->drawable->second()->x(),
                $this->drawable->second()->y(),
                $this->drawable->last()->x(),
                $this->drawable->last()->y()
            );
        } elseif ($this->drawable->count() === 4) {
            $drawing->pathCurveToAbsolute(
                $this->drawable->second()->x(),
                $this->drawable->second()->y(),
                $this->drawable->third()->x(),
                $this->drawable->third()->y(),
                $this->drawable->last()->x(),
                $this->drawable->last()->y()
            );
        }
        $drawing->pathFinish();

        foreach ($image as $frame) {
            $frame->native()->drawImage($drawing);
        }

        return $image;
    }
}
