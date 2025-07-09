<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Gd\Analyzers;

use Kanhaiyanigam05\Image\Analyzers\ColorspaceAnalyzer as GenericColorspaceAnalyzer;
use Kanhaiyanigam05\Image\Colors\Rgb\Colorspace;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;

class ColorspaceAnalyzer extends GenericColorspaceAnalyzer implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see AnalyzerInterface::analyze()
     */
    public function analyze(ImageInterface $image): mixed
    {
        return new Colorspace();
    }
}
