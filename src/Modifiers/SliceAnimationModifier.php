<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Modifiers;

use Kanhaiyanigam05\Image\Drivers\SpecializableModifier;

class SliceAnimationModifier extends SpecializableModifier
{
    public function __construct(public int $offset = 0, public ?int $length = null)
    {
        //
    }
}
