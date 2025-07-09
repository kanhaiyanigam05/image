<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Imagick\Analyzers;

use Kanhaiyanigam05\Image\Analyzers\ResolutionAnalyzer as GenericResolutionAnalyzer;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;
use Kanhaiyanigam05\Image\Resolution;

class ResolutionAnalyzer extends GenericResolutionAnalyzer implements SpecializedInterface
{
    public function analyze(ImageInterface $image): mixed
    {
        $imagick = $image->core()->native();
        $imageResolution = $imagick->getImageResolution();

        return new Resolution(
            $imageResolution['x'],
            $imageResolution['y'],
            $imagick->getImageUnits(),
        );
    }
}
