<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Imagick\Modifiers;

use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SizeInterface;

class ResizeDownModifier extends ResizeModifier
{
    protected function getAdjustedSize(ImageInterface $image): SizeInterface
    {
        return $image->size()->resizeDown($this->width, $this->height);
    }
}
