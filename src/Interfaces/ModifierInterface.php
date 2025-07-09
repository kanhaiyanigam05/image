<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Interfaces;

use Kanhaiyanigam05\Image\Exceptions\RuntimeException;

interface ModifierInterface
{
    /**
     * Apply modifications of the current modifier to the given image
     *
     * @throws RuntimeException
     */
    public function apply(ImageInterface $image): ImageInterface;
}
