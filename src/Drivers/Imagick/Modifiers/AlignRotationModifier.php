<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Imagick\Modifiers;

use Imagick;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;
use Kanhaiyanigam05\Image\Modifiers\AlignRotationModifier as GenericAlignRotationModifier;

class AlignRotationModifier extends GenericAlignRotationModifier implements SpecializedInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        switch ($image->core()->native()->getImageOrientation()) {
            case Imagick::ORIENTATION_TOPRIGHT: // 2
                $image->core()->native()->flopImage();
                break;

            case Imagick::ORIENTATION_BOTTOMRIGHT: // 3
                $image->core()->native()->rotateImage("#000", 180);
                break;

            case Imagick::ORIENTATION_BOTTOMLEFT: // 4
                $image->core()->native()->rotateImage("#000", 180);
                $image->core()->native()->flopImage();
                break;

            case Imagick::ORIENTATION_LEFTTOP: // 5
                $image->core()->native()->rotateImage("#000", -270);
                $image->core()->native()->flopImage();
                break;

            case Imagick::ORIENTATION_RIGHTTOP: // 6
                $image->core()->native()->rotateImage("#000", -270);
                break;

            case Imagick::ORIENTATION_RIGHTBOTTOM: // 7
                $image->core()->native()->rotateImage("#000", -90);
                $image->core()->native()->flopImage();
                break;

            case Imagick::ORIENTATION_LEFTBOTTOM: // 8
                $image->core()->native()->rotateImage("#000", -90);
                break;
        }

        // set new orientation in image
        $image->core()->native()->setImageOrientation(Imagick::ORIENTATION_TOPLEFT);

        return $image;
    }
}
