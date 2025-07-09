<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Imagick\Modifiers;

use Kanhaiyanigam05\Image\Exceptions\InputException;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;
use Kanhaiyanigam05\Image\Modifiers\QuantizeColorsModifier as GenericQuantizeColorsModifier;

class QuantizeColorsModifier extends GenericQuantizeColorsModifier implements SpecializedInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        if ($this->limit <= 0) {
            throw new InputException('Quantization limit must be greater than 0.');
        }

        // no color reduction if the limit is higher than the colors in the img
        if ($this->limit > $image->core()->native()->getImageColors()) {
            return $image;
        }

        foreach ($image as $frame) {
            $frame->native()->quantizeImage(
                $this->limit,
                $frame->native()->getImageColorspace(),
                0,
                false,
                false
            );
        }

        return $image;
    }
}
