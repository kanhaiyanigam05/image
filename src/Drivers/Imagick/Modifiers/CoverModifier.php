<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Imagick\Modifiers;

use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;
use Kanhaiyanigam05\Image\Modifiers\CoverModifier as GenericCoverModifier;

class CoverModifier extends GenericCoverModifier implements SpecializedInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $crop = $this->getCropSize($image);
        $resize = $this->getResizeSize($crop);

        foreach ($image as $frame) {
            $frame->native()->cropImage(
                $crop->width(),
                $crop->height(),
                $crop->pivot()->x(),
                $crop->pivot()->y()
            );

            $frame->native()->scaleImage(
                $resize->width(),
                $resize->height()
            );

            $frame->native()->setImagePage(0, 0, 0, 0);
        }

        return $image;
    }
}
