<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Colors\Rgb;

use Kanhaiyanigam05\Image\Colors\Hsv\Color as HsvColor;
use Kanhaiyanigam05\Image\Colors\Hsl\Color as HslColor;
use Kanhaiyanigam05\Image\Colors\Cmyk\Color as CmykColor;
use Kanhaiyanigam05\Image\Exceptions\ColorException;
use Kanhaiyanigam05\Image\Interfaces\ColorChannelInterface;
use Kanhaiyanigam05\Image\Interfaces\ColorInterface;
use Kanhaiyanigam05\Image\Interfaces\ColorspaceInterface;

class Colorspace implements ColorspaceInterface
{
    /**
     * Channel class names of colorspace
     *
     * @var array<string>
     */
    public static array $channels = [
        Channels\Red::class,
        Channels\Green::class,
        Channels\Blue::class,
        Channels\Alpha::class
    ];

    /**
     * {@inheritdoc}
     *
     * @see ColorspaceInterface::colorFromNormalized()
     */
    public function colorFromNormalized(array $normalized): ColorInterface
    {
        return new Color(...array_map(
            fn($classname, float $value_normalized) => (new $classname(normalized: $value_normalized))->value(),
            self::$channels,
            $normalized,
        ));
    }

    /**
     * @throws ColorException
     */
    public function importColor(ColorInterface $color): ColorInterface
    {
        return match ($color::class) {
            CmykColor::class => $this->importCmykColor($color),
            HsvColor::class => $this->importHsvColor($color),
            HslColor::class => $this->importHslColor($color),
            default => $color,
        };
    }

    /**
     * @throws ColorException
     */
    protected function importCmykColor(ColorInterface $color): ColorInterface
    {
        if (!($color instanceof CmykColor)) {
            throw new ColorException('Unabled to import color of type ' . $color::class . '.');
        }

        return new Color(
            (int) (255 * (1 - $color->cyan()->normalize()) * (1 - $color->key()->normalize())),
            (int) (255 * (1 - $color->magenta()->normalize()) * (1 - $color->key()->normalize())),
            (int) (255 * (1 - $color->yellow()->normalize()) * (1 - $color->key()->normalize())),
        );
    }

    /**
     * @throws ColorException
     */
    protected function importHsvColor(ColorInterface $color): ColorInterface
    {
        if (!($color instanceof HsvColor)) {
            throw new ColorException('Unabled to import color of type ' . $color::class . '.');
        }

        $chroma = $color->value()->normalize() * $color->saturation()->normalize();
        $hue = $color->hue()->normalize() * 6;
        $x = $chroma * (1 - abs(fmod($hue, 2) - 1));

        // connect channel values
        $values = match (true) {
            $hue < 1 => [$chroma, $x, 0],
            $hue < 2 => [$x, $chroma, 0],
            $hue < 3 => [0, $chroma, $x],
            $hue < 4 => [0, $x, $chroma],
            $hue < 5 => [$x, 0, $chroma],
            default => [$chroma, 0, $x],
        };

        // add to each value
        $values = array_map(fn(float|int $value): float => $value + $color->value()->normalize() - $chroma, $values);
        $values[] = 1; // append alpha channel value

        return $this->colorFromNormalized($values);
    }

    /**
     * @throws ColorException
     */
    protected function importHslColor(ColorInterface $color): ColorInterface
    {
        if (!($color instanceof HslColor)) {
            throw new ColorException('Unabled to import color of type ' . $color::class . '.');
        }

        // normalized values of hsl channels
        [$h, $s, $l] = array_map(
            fn(ColorChannelInterface $channel): float => $channel->normalize(),
            $color->channels()
        );

        $c = (1 - abs(2 * $l - 1)) * $s;
        $x = $c * (1 - abs(fmod($h * 6, 2) - 1));
        $m = $l - $c / 2;

        $values = match (true) {
            $h < 1 / 6 => [$c, $x, 0],
            $h < 2 / 6 => [$x, $c, 0],
            $h < 3 / 6 => [0, $c, $x],
            $h < 4 / 6 => [0, $x, $c],
            $h < 5 / 6 => [$x, 0, $c],
            default => [$c, 0, $x],
        };

        $values = array_map(fn(float|int $value): float => $value + $m, $values);
        $values[] = 1; // append alpha channel value

        return $this->colorFromNormalized($values);
    }
}
