<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Modifiers;

use Kanhaiyanigam05\Image\Drivers\SpecializableModifier;
use Kanhaiyanigam05\Image\Interfaces\PointInterface;

class DrawPixelModifier extends SpecializableModifier
{
    /**
     * Create new modifier object
     *
     * @return void
     */
    public function __construct(
        public PointInterface $position,
        public mixed $color
    ) {
        //
    }
}
