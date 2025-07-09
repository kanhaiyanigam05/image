<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Gd\Analyzers;

use Kanhaiyanigam05\Image\Collection;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;

class PixelColorsAnalyzer extends PixelColorAnalyzer
{
    /**
     * {@inheritdoc}
     *
     * @see AnalyzerInterface::analyze()
     */
    public function analyze(ImageInterface $image): mixed
    {
        $colors = new Collection();
        $colorspace = $image->colorspace();

        foreach ($image as $frame) {
            $colors->push(
                parent::colorAt($colorspace, $frame->native())
            );
        }

        return $colors;
    }
}
