<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Gd\Modifiers;

use Kanhaiyanigam05\Image\Drivers\Gd\Cloner;
use Kanhaiyanigam05\Image\Exceptions\InputException;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;
use Kanhaiyanigam05\Image\Modifiers\QuantizeColorsModifier as GenericQuantizeColorsModifier;

class QuantizeColorsModifier extends GenericQuantizeColorsModifier implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        if ($this->limit <= 0) {
            throw new InputException('Quantization limit must be greater than 0.');
        }

        // no color reduction if the limit is higher than the colors in the img
        $colorCount = imagecolorstotal($image->core()->native());
        if ($colorCount > 0 && $this->limit > $colorCount) {
            return $image;
        }

        $width = $image->width();
        $height = $image->height();

        $background = $this->driver()->colorProcessor($image->colorspace())->colorToNative(
            $this->driver()->handleInput($this->background)
        );

        $blendingColor = $this->driver()->handleInput(
            $this->driver()->config()->blendingColor
        );

        foreach ($image as $frame) {
            // create new image for color quantization
            $reduced = Cloner::cloneEmpty($frame->native(), background: $blendingColor);

            // fill with background
            imagefill($reduced, 0, 0, $background);

            // set transparency
            imagecolortransparent($reduced, $background);

            // copy original image (colors are limited automatically in the copy process)
            imagecopy($reduced, $frame->native(), 0, 0, 0, 0, $width, $height);

            // gd library does not support color quantization directly therefore the
            // colors are decrease by transforming the image to a palette version
            imagetruecolortopalette($reduced, true, $this->limit);

            $frame->setNative($reduced);
        }

        return $image;
    }
}
