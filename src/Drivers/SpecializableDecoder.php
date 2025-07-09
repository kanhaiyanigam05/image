<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers;

use Kanhaiyanigam05\Image\Exceptions\DecoderException;
use Kanhaiyanigam05\Image\Interfaces\ColorInterface;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializableInterface;
use Kanhaiyanigam05\Image\Traits\CanBeDriverSpecialized;

abstract class SpecializableDecoder extends AbstractDecoder implements SpecializableInterface
{
    use CanBeDriverSpecialized;

    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::decode()
     */
    public function decode(mixed $input): ImageInterface|ColorInterface
    {
        throw new DecoderException('Decoder must be specialized by the driver first.');
    }
}
