<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Imagick\Analyzers;

use Kanhaiyanigam05\Image\Analyzers\WidthAnalyzer as GenericWidthAnalyzer;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;

class WidthAnalyzer extends GenericWidthAnalyzer implements SpecializedInterface
{
    public function analyze(ImageInterface $image): mixed
    {
        return $image->core()->native()->getImageWidth();
    }
}
