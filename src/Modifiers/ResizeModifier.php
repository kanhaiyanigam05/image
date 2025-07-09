<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Modifiers;

use Kanhaiyanigam05\Image\Drivers\SpecializableModifier;

class ResizeModifier extends SpecializableModifier
{
    /**
     * Create new modifier object
     *
     * @return void
     */
    public function __construct(public ?int $width = null, public ?int $height = null)
    {
        //
    }
}
