<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Modifiers;

use Kanhaiyanigam05\Image\Drivers\SpecializableModifier;

class PixelateModifier extends SpecializableModifier
{
    public function __construct(public int $size)
    {
        //
    }
}
