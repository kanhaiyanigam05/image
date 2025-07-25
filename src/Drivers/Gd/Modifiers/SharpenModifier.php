<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Gd\Modifiers;

use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;
use Kanhaiyanigam05\Image\Modifiers\SharpenModifier as GenericSharpenModifier;

class SharpenModifier extends GenericSharpenModifier implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $matrix = $this->matrix();
        foreach ($image as $frame) {
            imageconvolution($frame->native(), $matrix, 1, 0);
        }

        return $image;
    }

    /**
     * Create matrix to be used by imageconvolution()
     *
     * @return array<array<float>>
     */
    private function matrix(): array
    {
        $min = $this->amount >= 10 ? $this->amount * -0.01 : 0;
        $max = $this->amount * -0.025;
        $abs = ((4 * $min + 4 * $max) * -1) + 1;

        return [
            [$min, $max, $min],
            [$max, $abs, $max],
            [$min, $max, $min]
        ];
    }
}
