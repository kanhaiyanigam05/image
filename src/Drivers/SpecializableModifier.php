<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers;

use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\ModifierInterface;

abstract class SpecializableModifier extends Specializable implements ModifierInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        return $image->modify($this);
    }
}
