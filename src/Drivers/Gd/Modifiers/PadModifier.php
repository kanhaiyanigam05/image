<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Gd\Modifiers;

use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SizeInterface;

class PadModifier extends ContainModifier
{
    public function getCropSize(ImageInterface $image): SizeInterface
    {
        return $image->size()
            ->containMax(
                $this->width,
                $this->height
            )
            ->alignPivotTo(
                $this->getResizeSize($image),
                $this->position
            );
    }
}
