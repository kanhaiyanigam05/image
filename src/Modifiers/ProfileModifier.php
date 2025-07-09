<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Modifiers;

use Kanhaiyanigam05\Image\Drivers\SpecializableModifier;
use Kanhaiyanigam05\Image\Interfaces\ProfileInterface;

class ProfileModifier extends SpecializableModifier
{
    public function __construct(public ProfileInterface $profile)
    {
        //
    }
}
