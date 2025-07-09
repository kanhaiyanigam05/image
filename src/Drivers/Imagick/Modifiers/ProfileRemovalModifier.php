<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Imagick\Modifiers;

use Kanhaiyanigam05\Image\Exceptions\ColorException;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;
use Kanhaiyanigam05\Image\Modifiers\ProfileRemovalModifier as GenericProfileRemovalModifier;

class ProfileRemovalModifier extends GenericProfileRemovalModifier implements SpecializedInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $imagick = $image->core()->native();
        $result = $imagick->profileImage('icc', null);

        if ($result === false) {
            throw new ColorException('ICC color profile could not be removed.');
        }

        return $image;
    }
}
