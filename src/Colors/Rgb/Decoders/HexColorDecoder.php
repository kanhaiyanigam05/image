<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Colors\Rgb\Decoders;

use Kanhaiyanigam05\Image\Colors\Rgb\Color;
use Kanhaiyanigam05\Image\Drivers\AbstractDecoder;
use Kanhaiyanigam05\Image\Exceptions\DecoderException;
use Kanhaiyanigam05\Image\Interfaces\ColorInterface;
use Kanhaiyanigam05\Image\Interfaces\DecoderInterface;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;

class HexColorDecoder extends AbstractDecoder implements DecoderInterface
{
    /**
     * Decode hexadecimal rgb colors with and without transparency
     */
    public function decode(mixed $input): ImageInterface|ColorInterface
    {
        if (!is_string($input)) {
            throw new DecoderException('Unable to decode input');
        }

        $pattern = '/^#?(?P<hex>[a-f\d]{3}(?:[a-f\d]?|(?:[a-f\d]{3}(?:[a-f\d]{2})?)?)\b)$/i';
        if (preg_match($pattern, $input, $matches) != 1) {
            throw new DecoderException('Unable to decode input');
        }

        $values = match (strlen($matches['hex'])) {
            3, 4 => str_split($matches['hex']),
            6, 8 => str_split($matches['hex'], 2),
            default => throw new DecoderException('Unable to decode input'),
        };

        $values = array_map(function (string $value): float|int {
            return match (strlen($value)) {
                1 => hexdec($value . $value),
                2 => hexdec($value),
                default => throw new DecoderException('Unable to decode input'),
            };
        }, $values);

        return new Color(...$values);
    }
}
