<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Gd\Analyzers;

use GdImage;
use Kanhaiyanigam05\Image\Analyzers\PixelColorAnalyzer as GenericPixelColorAnalyzer;
use Kanhaiyanigam05\Image\Exceptions\ColorException;
use Kanhaiyanigam05\Image\Exceptions\GeometryException;
use Kanhaiyanigam05\Image\Interfaces\ColorInterface;
use Kanhaiyanigam05\Image\Interfaces\ColorspaceInterface;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;

class PixelColorAnalyzer extends GenericPixelColorAnalyzer implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see AnalyzerInterface::analyze()
     */
    public function analyze(ImageInterface $image): mixed
    {
        return $this->colorAt(
            $image->colorspace(),
            $image->core()->frame($this->frame_key)->native()
        );
    }

    /**
     * @throws GeometryException
     * @throws ColorException
     */
    protected function colorAt(ColorspaceInterface $colorspace, GdImage $gd): ColorInterface
    {
        $index = @imagecolorat($gd, $this->x, $this->y);

        if (!imageistruecolor($gd)) {
            $index = imagecolorsforindex($gd, $index);
        }

        if ($index === false) {
            throw new GeometryException(
                'The specified position is not in the valid image area.'
            );
        }

        return $this->driver()->colorProcessor($colorspace)->nativeToColor($index);
    }
}
