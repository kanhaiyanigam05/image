<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Colors\Cmyk\Decoders;

use Kanhaiyanigam05\Image\Colors\Cmyk\Color;
use Kanhaiyanigam05\Image\Drivers\AbstractDecoder;
use Kanhaiyanigam05\Image\Exceptions\DecoderException;
use Kanhaiyanigam05\Image\Interfaces\ColorInterface;
use Kanhaiyanigam05\Image\Interfaces\DecoderInterface;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;

class StringColorDecoder extends AbstractDecoder implements DecoderInterface
{
    /**
     * Decode CMYK color strings
     */
    public function decode(mixed $input): ImageInterface|ColorInterface
    {
        if (!is_string($input)) {
            throw new DecoderException('Unable to decode input');
        }

        $pattern = '/^cmyk\((?P<c>[0-9\.]+%?), ?(?P<m>[0-9\.]+%?), ?(?P<y>[0-9\.]+%?), ?(?P<k>[0-9\.]+%?)\)$/i';
        if (preg_match($pattern, $input, $matches) != 1) {
            throw new DecoderException('Unable to decode input');
        }

        $values = array_map(function (string $value): int {
            return intval(round(floatval(trim(str_replace('%', '', $value)))));
        }, [$matches['c'], $matches['m'], $matches['y'], $matches['k']]);

        return new Color(...$values);
    }
}
