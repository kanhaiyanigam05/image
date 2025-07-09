<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Imagick\Analyzers;

use Imagick;
use Kanhaiyanigam05\Image\Analyzers\ColorspaceAnalyzer as GenericColorspaceAnalyzer;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Colors\Cmyk\Colorspace as CmykColorspace;
use Kanhaiyanigam05\Image\Colors\Rgb\Colorspace as RgbColorspace;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;

class ColorspaceAnalyzer extends GenericColorspaceAnalyzer implements SpecializedInterface
{
    public function analyze(ImageInterface $image): mixed
    {
        return match ($image->core()->native()->getImageColorspace()) {
            Imagick::COLORSPACE_CMYK => new CmykColorspace(),
            default => new RgbColorspace(),
        };
    }
}
