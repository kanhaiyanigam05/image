<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Gd\Analyzers;

use Kanhaiyanigam05\Image\Analyzers\ResolutionAnalyzer as GenericResolutionAnalyzer;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;
use Kanhaiyanigam05\Image\Resolution;

class ResolutionAnalyzer extends GenericResolutionAnalyzer implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see AnalyzerInterface::analyze()
     */
    public function analyze(ImageInterface $image): mixed
    {
        return new Resolution(...imageresolution($image->core()->native()));
    }
}
