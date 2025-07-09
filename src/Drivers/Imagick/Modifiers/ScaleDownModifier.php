<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Imagick\Modifiers;

use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SizeInterface;

class ScaleDownModifier extends ResizeModifier
{
    protected function getAdjustedSize(ImageInterface $image): SizeInterface
    {
        return $image->size()->scaleDown($this->width, $this->height);
    }
}
