<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Modifiers;

use Kanhaiyanigam05\Image\Drivers\SpecializableModifier;

class RotateModifier extends SpecializableModifier
{
    public function __construct(public float $angle, public mixed $background)
    {
        //
    }

    /**
     * Restrict rotations beyond 360 degrees
     * because the end result is the same
     */
    public function rotationAngle(): float
    {
        return fmod($this->angle, 360);
    }
}
