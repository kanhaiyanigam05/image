<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Colors\Rgb\Decoders;

use Kanhaiyanigam05\Image\Colors\Rgb\Color;
use Kanhaiyanigam05\Image\Drivers\AbstractDecoder;
use Kanhaiyanigam05\Image\Exceptions\DecoderException;
use Kanhaiyanigam05\Image\Interfaces\ColorInterface;
use Kanhaiyanigam05\Image\Interfaces\DecoderInterface;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;

class StringColorDecoder extends AbstractDecoder implements DecoderInterface
{
    /**
     * Decode rgb color strings
     */
    public function decode(mixed $input): ImageInterface|ColorInterface
    {
        if (!is_string($input)) {
            throw new DecoderException('Unable to decode input');
        }

        $pattern = '/^s?rgba?\((?P<r>[0-9\.]+%?), ?(?P<g>[0-9\.]+%?), ?(?P<b>[0-9\.]+%?)' .
            '(?:, ?(?P<a>(?:1)|(?:1\.0*)|(?:0)|(?:0?\.\d+%?)|(?:\d{1,3}%)))?\)$/i';
        if (preg_match($pattern, $input, $matches) != 1) {
            throw new DecoderException('Unable to decode input');
        }

        // rgb values
        $values = array_map(function (string $value): int {
            return match (strpos($value, '%')) {
                false => intval(trim($value)),
                default => intval(round(floatval(trim(str_replace('%', '', $value))) / 100 * 255)),
            };
        }, [$matches['r'], $matches['g'], $matches['b']]);

        // alpha value
        if (array_key_exists('a', $matches)) {
            $values[] = match (true) {
                strpos($matches['a'], '%') => round(intval(trim(str_replace('%', '', $matches['a']))) / 2.55),
                default => intval(round(floatval(trim($matches['a'])) * 255)),
            };
        }

        return new Color(...$values);
    }
}
