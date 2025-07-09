<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Analyzers;

use Kanhaiyanigam05\Image\Drivers\SpecializableAnalyzer;

class PixelColorsAnalyzer extends SpecializableAnalyzer
{
    public function __construct(
        public int $x,
        public int $y
    ) {
        //
    }
}
