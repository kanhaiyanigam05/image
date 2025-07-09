<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Gd\Modifiers;

use Kanhaiyanigam05\Image\Drivers\Gd\Cloner;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;
use Kanhaiyanigam05\Image\Modifiers\BlendTransparencyModifier as GenericBlendTransparencyModifier;

class BlendTransparencyModifier extends GenericBlendTransparencyModifier implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $blendingColor = $this->blendingColor($this->driver());

        foreach ($image as $frame) {
            // create new canvas with blending color as background
            $modified = Cloner::cloneBlended(
                $frame->native(),
                background: $blendingColor
            );

            // set new gd image
            $frame->setNative($modified);
        }

        return $image;
    }
}
