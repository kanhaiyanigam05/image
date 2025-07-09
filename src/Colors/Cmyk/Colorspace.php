<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Colors\Cmyk;

use Kanhaiyanigam05\Image\Colors\Rgb\Color as RgbColor;
use Kanhaiyanigam05\Image\Colors\Cmyk\Color as CmykColor;
use Kanhaiyanigam05\Image\Colors\Hsv\Color as HsvColor;
use Kanhaiyanigam05\Image\Colors\Hsl\Color as HslColor;
use Kanhaiyanigam05\Image\Colors\Rgb\Colorspace as RgbColorspace;
use Kanhaiyanigam05\Image\Exceptions\ColorException;
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
        Channels\Cyan::class,
        Channels\Magenta::class,
        Channels\Yellow::class,
        Channels\Key::class
    ];

    /**
     * {@inheritdoc}
     *
     * @see ColorspaceInterface::createColor()
     */
    public function colorFromNormalized(array $normalized): ColorInterface
    {
        return new Color(...array_map(
            fn(string $classname, float $value_normalized) => (new $classname(normalized: $value_normalized))->value(),
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
            RgbColor::class => $this->importRgbColor($color),
            HsvColor::class => $this->importRgbColor($color->convertTo(RgbColorspace::class)),
            HslColor::class => $this->importRgbColor($color->convertTo(RgbColorspace::class)),
            default => $color,
        };
    }

    /**
     * @throws ColorException
     */
    protected function importRgbColor(ColorInterface $color): CmykColor
    {
        if (!($color instanceof RgbColor)) {
            throw new ColorException('Unabled to import color of type ' . $color::class . '.');
        }

        $c = (255 - $color->red()->value()) / 255.0 * 100;
        $m = (255 - $color->green()->value()) / 255.0 * 100;
        $y = (255 - $color->blue()->value()) / 255.0 * 100;
        $k = intval(round(min([$c, $m, $y])));

        $c = intval(round($c - $k));
        $m = intval(round($m - $k));
        $y = intval(round($y - $k));

        return new CmykColor($c, $m, $y, $k);
    }
}
