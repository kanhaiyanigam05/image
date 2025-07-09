<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Imagick\Analyzers;

use Imagick;
use Kanhaiyanigam05\Image\Analyzers\PixelColorAnalyzer as GenericPixelColorAnalyzer;
use Kanhaiyanigam05\Image\Exceptions\ColorException;
use Kanhaiyanigam05\Image\Interfaces\ColorInterface;
use Kanhaiyanigam05\Image\Interfaces\ColorspaceInterface;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;

class PixelColorAnalyzer extends GenericPixelColorAnalyzer implements SpecializedInterface
{
    public function analyze(ImageInterface $image): mixed
    {
        return $this->colorAt(
            $image->colorspace(),
            $image->core()->frame($this->frame_key)->native()
        );
    }

    /**
     * @throws ColorException
     */
    protected function colorAt(ColorspaceInterface $colorspace, Imagick $imagick): ColorInterface
    {
        return $this->driver()
            ->colorProcessor($colorspace)
            ->nativeToColor(
                $imagick->getImagePixelColor($this->x, $this->y)
            );
    }
}
