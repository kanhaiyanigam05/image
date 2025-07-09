<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Gd\Decoders;

use Kanhaiyanigam05\Image\Drivers\SpecializableDecoder;
use Kanhaiyanigam05\Image\Exceptions\DecoderException;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;
use Kanhaiyanigam05\Image\MediaType;

abstract class AbstractDecoder extends SpecializableDecoder implements SpecializedInterface
{
    /**
     * Return media (mime) type of the file at given file path
     *
     * @throws DecoderException
     */
    protected function getMediaTypeByFilePath(string $filepath): MediaType
    {
        $info = @getimagesize($filepath);

        if (!is_array($info)) {
            throw new DecoderException('Unable to detect media (MIME) from data in file path.');
        }

        return MediaType::from($info['mime']);
    }

    /**
     * Return media (mime) type of the given image data
     *
     * @throws DecoderException
     */
    protected function getMediaTypeByBinary(string $data): MediaType
    {
        $info = @getimagesizefromstring($data);

        if (!is_array($info)) {
            throw new DecoderException('Unable to detect media (MIME) from binary data.');
        }

        return MediaType::from($info['mime']);
    }
}
