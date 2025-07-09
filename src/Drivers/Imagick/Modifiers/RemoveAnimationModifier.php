<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Imagick\Modifiers;

use Imagick;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;
use Kanhaiyanigam05\Image\Modifiers\RemoveAnimationModifier as GenericRemoveAnimationModifier;

class RemoveAnimationModifier extends GenericRemoveAnimationModifier implements SpecializedInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        // create new imagick with just one image
        $imagick = new Imagick();
        $frame = $this->selectedFrame($image);
        $imagick->addImage($frame->native()->getImage());

        // set new imagick to image
        $image->core()->setNative($imagick);

        return $image;
    }
}
