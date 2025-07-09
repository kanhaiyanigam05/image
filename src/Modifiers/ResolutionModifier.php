<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Modifiers;

use Kanhaiyanigam05\Image\Drivers\SpecializableModifier;

class ResolutionModifier extends SpecializableModifier
{
    /**
     * Create new modifier object
     *
     * @return void
     */
    public function __construct(public float $x, public float $y)
    {
        //
    }
}
