<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers;

use Kanhaiyanigam05\Image\Interfaces\SpecializableInterface;
use Kanhaiyanigam05\Image\Traits\CanBeDriverSpecialized;

abstract class SpecializableEncoder extends AbstractEncoder implements SpecializableInterface
{
    use CanBeDriverSpecialized;
}
