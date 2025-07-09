<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Imagick;

use Imagick;
use ImagickPixel;
use Kanhaiyanigam05\Image\Colors\Cmyk\Colorspace as CmykColorspace;
use Kanhaiyanigam05\Image\Interfaces\ColorInterface;
use Kanhaiyanigam05\Image\Interfaces\ColorProcessorInterface;
use Kanhaiyanigam05\Image\Interfaces\ColorspaceInterface;

class ColorProcessor implements ColorProcessorInterface
{
    public function __construct(protected ColorspaceInterface $colorspace)
    {
        //
    }

    public function colorToNative(ColorInterface $color): ImagickPixel
    {
        return new ImagickPixel(
            (string) $color->convertTo($this->colorspace)
        );
    }

    public function nativeToColor(mixed $native): ColorInterface
    {
        return match ($this->colorspace::class) {
            CmykColorspace::class => $this->colorspace->colorFromNormalized([
                $native->getColorValue(Imagick::COLOR_CYAN),
                $native->getColorValue(Imagick::COLOR_MAGENTA),
                $native->getColorValue(Imagick::COLOR_YELLOW),
                $native->getColorValue(Imagick::COLOR_BLACK),
            ]),
            default => $this->colorspace->colorFromNormalized([
                $native->getColorValue(Imagick::COLOR_RED),
                $native->getColorValue(Imagick::COLOR_GREEN),
                $native->getColorValue(Imagick::COLOR_BLUE),
                $native->getColorValue(Imagick::COLOR_ALPHA),
            ]),
        };
    }
}
