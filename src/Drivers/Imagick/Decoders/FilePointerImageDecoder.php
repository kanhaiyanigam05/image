<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Imagick\Decoders;

use Kanhaiyanigam05\Image\Exceptions\DecoderException;
use Kanhaiyanigam05\Image\Interfaces\ColorInterface;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;

class FilePointerImageDecoder extends BinaryImageDecoder
{
    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::decode()
     */
    public function decode(mixed $input): ImageInterface|ColorInterface
    {
        if (!is_resource($input) || !in_array(get_resource_type($input), ['file', 'stream'])) {
            throw new DecoderException('Unable to decode input');
        }

        $contents = '';
        @rewind($input);
        while (!feof($input)) {
            $contents .= fread($input, 1024);
        }

        return parent::decode($contents);
    }
}
