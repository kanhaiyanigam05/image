<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Gd\Modifiers;

use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;
use Kanhaiyanigam05\Image\Modifiers\ResizeCanvasModifier as GenericResizeCanvasModifier;

class ResizeCanvasModifier extends GenericResizeCanvasModifier implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $cropSize = $this->cropSize($image);

        $image->modify(new CropModifier(
            $cropSize->width(),
            $cropSize->height(),
            $cropSize->pivot()->x(),
            $cropSize->pivot()->y(),
            $this->background,
        ));

        return $image;
    }
}
