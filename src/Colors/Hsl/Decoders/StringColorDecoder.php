<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Colors\Hsl\Decoders;

use Kanhaiyanigam05\Image\Colors\Hsl\Color;
use Kanhaiyanigam05\Image\Drivers\AbstractDecoder;
use Kanhaiyanigam05\Image\Exceptions\DecoderException;
use Kanhaiyanigam05\Image\Interfaces\ColorInterface;
use Kanhaiyanigam05\Image\Interfaces\DecoderInterface;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;

class StringColorDecoder extends AbstractDecoder implements DecoderInterface
{
    /**
     * Decode hsl color strings
     */
    public function decode(mixed $input): ImageInterface|ColorInterface
    {
        if (!is_string($input)) {
            throw new DecoderException('Unable to decode input');
        }

        $pattern = '/^hsl\((?P<h>[0-9\.]+), ?(?P<s>[0-9\.]+%?), ?(?P<l>[0-9\.]+%?)?\)$/i';
        if (preg_match($pattern, $input, $matches) != 1) {
            throw new DecoderException('Unable to decode input');
        }

        $values = array_map(function (string $value): int {
            return match (strpos($value, '%')) {
                false => intval(trim($value)),
                default => intval(trim(str_replace('%', '', $value))),
            };
        }, [$matches['h'], $matches['s'], $matches['l']]);

        return new Color(...$values);
    }
}
