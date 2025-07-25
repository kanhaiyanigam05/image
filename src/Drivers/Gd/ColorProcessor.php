<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Gd;

use Kanhaiyanigam05\Image\Colors\Rgb\Channels\Alpha;
use Kanhaiyanigam05\Image\Colors\Rgb\Channels\Blue;
use Kanhaiyanigam05\Image\Colors\Rgb\Channels\Green;
use Kanhaiyanigam05\Image\Colors\Rgb\Channels\Red;
use Kanhaiyanigam05\Image\Colors\Rgb\Color;
use Kanhaiyanigam05\Image\Colors\Rgb\Colorspace;
use Kanhaiyanigam05\Image\Exceptions\ColorException;
use Kanhaiyanigam05\Image\Interfaces\ColorInterface;
use Kanhaiyanigam05\Image\Interfaces\ColorProcessorInterface;
use Kanhaiyanigam05\Image\Interfaces\ColorspaceInterface;

class ColorProcessor implements ColorProcessorInterface
{
    /**
     * Create new color processor object
     *
     * @return void
     */
    public function __construct(protected ColorspaceInterface $colorspace = new Colorspace())
    {
        //
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorProcessorInterface::colorToNative()
     */
    public function colorToNative(ColorInterface $color): int
    {
        // convert color to colorspace
        $color = $color->convertTo($this->colorspace);

        // gd only supports rgb so the channels can be accessed directly
        $r = $color->channel(Red::class)->value();
        $g = $color->channel(Green::class)->value();
        $b = $color->channel(Blue::class)->value();
        $a = $color->channel(Alpha::class)->value();

        // convert alpha value to gd alpha
        // ([opaque]255-0[transparent]) to ([opaque]0-127[transparent])
        $a = (int) $this->convertRange($a, 0, 255, 127, 0);

        return ($a << 24) + ($r << 16) + ($g << 8) + $b;
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorProcessorInterface::nativeToColor()
     */
    public function nativeToColor(mixed $value): ColorInterface
    {
        if (!is_int($value) && !is_array($value)) {
            throw new ColorException('GD driver can only decode colors in integer and array format.');
        }

        if (is_array($value)) {
            // array conversion
            if (!$this->isValidArrayColor($value)) {
                throw new ColorException(
                    'GD driver can only decode array color format array{red: int, green: int, blue: int, alpha: int}.',
                );
            }

            $r = $value['red'];
            $g = $value['green'];
            $b = $value['blue'];
            $a = $value['alpha'];
        } else {
            // integer conversion
            $a = ($value >> 24) & 0xFF;
            $r = ($value >> 16) & 0xFF;
            $g = ($value >> 8) & 0xFF;
            $b = $value & 0xFF;
        }

        // convert gd apha integer to Kanhaiyanigam05 alpha integer
        // ([opaque]0-127[transparent]) to ([opaque]255-0[transparent])
        $a = (int) static::convertRange($a, 127, 0, 0, 255);

        return new Color($r, $g, $b, $a);
    }

    /**
     * Convert input in range (min) to (max) to the corresponding value
     * in target range (targetMin) to (targetMax).
     */
    protected function convertRange(
        float|int $input,
        float|int $min,
        float|int $max,
        float|int $targetMin,
        float|int $targetMax
    ): float|int {
        return ceil(((($input - $min) * ($targetMax - $targetMin)) / ($max - $min)) + $targetMin);
    }

    /**
     * Check if given array is valid color format
     * array{red: int, green: int, blue: int, alpha: int}
     * i.e. result of imagecolorsforindex()
     *
     * @param array<mixed> $color
     */
    private function isValidArrayColor(array $color): bool
    {
        if (!array_key_exists('red', $color)) {
            return false;
        }

        if (!array_key_exists('green', $color)) {
            return false;
        }

        if (!array_key_exists('blue', $color)) {
            return false;
        }

        if (!array_key_exists('alpha', $color)) {
            return false;
        }

        if (!is_int($color['red'])) {
            return false;
        }

        if (!is_int($color['green'])) {
            return false;
        }

        if (!is_int($color['blue'])) {
            return false;
        }

        if (!is_int($color['alpha'])) {
            return false;
        }

        return true;
    }
}
