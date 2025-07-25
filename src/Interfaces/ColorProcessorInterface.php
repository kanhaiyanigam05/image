<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Interfaces;

use Kanhaiyanigam05\Image\Exceptions\ColorException;

interface ColorProcessorInterface
{
    /**
     * Turn given color in the driver's color implementation
     *
     * @throws ColorException
     */
    public function colorToNative(ColorInterface $color): mixed;

    /**
     * Turn the given driver's definition of a color into a color object
     *
     * @throws ColorException
     */
    public function nativeToColor(mixed $native): ColorInterface;
}
