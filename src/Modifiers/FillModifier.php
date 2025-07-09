<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Modifiers;

use Kanhaiyanigam05\Image\Drivers\SpecializableModifier;
use Kanhaiyanigam05\Image\Interfaces\PointInterface;

class FillModifier extends SpecializableModifier
{
    public function __construct(
        public mixed $color,
        public ?PointInterface $position = null
    ) {
        //
    }

    public function hasPosition(): bool
    {
        return $this->position instanceof PointInterface;
    }
}
