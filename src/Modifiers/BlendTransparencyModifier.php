<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Modifiers;

use Kanhaiyanigam05\Image\Colors\Rgb\Channels\Blue;
use Kanhaiyanigam05\Image\Colors\Rgb\Channels\Green;
use Kanhaiyanigam05\Image\Colors\Rgb\Channels\Red;
use Kanhaiyanigam05\Image\Colors\Rgb\Color;
use Kanhaiyanigam05\Image\Drivers\SpecializableModifier;
use Kanhaiyanigam05\Image\Exceptions\ColorException;
use Kanhaiyanigam05\Image\Exceptions\RuntimeException;
use Kanhaiyanigam05\Image\Interfaces\ColorInterface;
use Kanhaiyanigam05\Image\Interfaces\DriverInterface;

class BlendTransparencyModifier extends SpecializableModifier
{
    /**
     * Create new modifier object
     *
     * @return void
     */
    public function __construct(public mixed $color = null)
    {
        //
    }

    /**
     * Decode blending color of current modifier with given driver. Possible
     * (semi-)transparent alpha channel values are made opaque.
     *
     * @throws RuntimeException
     * @throws ColorException
     */
    protected function blendingColor(DriverInterface $driver): ColorInterface
    {
        // decode blending color
        $color = $driver->handleInput(
            $this->color ?: $driver->config()->blendingColor
        );

        // replace alpha channel value with opaque value
        if ($color->isTransparent()) {
            return new Color(
                $color->channel(Red::class)->value(),
                $color->channel(Green::class)->value(),
                $color->channel(Blue::class)->value(),
            );
        }

        return $color;
    }
}
