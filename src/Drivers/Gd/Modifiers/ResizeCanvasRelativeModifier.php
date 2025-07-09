<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Gd\Modifiers;

use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SizeInterface;

class ResizeCanvasRelativeModifier extends ResizeCanvasModifier
{
    protected function cropSize(ImageInterface $image, bool $relative = false): SizeInterface
    {
        return parent::cropSize($image, true);
    }
}
