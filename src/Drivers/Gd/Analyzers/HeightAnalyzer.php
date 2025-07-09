<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Gd\Analyzers;

use Kanhaiyanigam05\Image\Analyzers\HeightAnalyzer as GenericHeightAnalyzer;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;

class HeightAnalyzer extends GenericHeightAnalyzer implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see AnalyzerInterface::analyze()
     */
    public function analyze(ImageInterface $image): mixed
    {
        return imagesy($image->core()->native());
    }
}
