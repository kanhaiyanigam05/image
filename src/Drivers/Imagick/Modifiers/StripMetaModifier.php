<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Imagick\Modifiers;

use Kanhaiyanigam05\Image\Collection;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\ModifierInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;

class StripMetaModifier implements ModifierInterface, SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see Kanhaiyanigam05\Image\Interfaces\ModifierInterface::apply()
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        // preserve icc profiles
        $profiles = $image->core()->native()->getImageProfiles('icc');

        // remove meta data
        $image->core()->native()->stripImage();
        $image->setExif(new Collection());

        if ($profiles !== []) {
            // re-apply icc profiles
            $image->core()->native()->profileImage("icc", $profiles['icc']);
        }
        return $image;
    }
}
