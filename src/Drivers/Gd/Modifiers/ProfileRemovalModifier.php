<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Gd\Modifiers;

use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;
use Kanhaiyanigam05\Image\Modifiers\ProfileRemovalModifier as GenericProfileRemovalModifier;

class ProfileRemovalModifier extends GenericProfileRemovalModifier implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        // Color profiles are not supported by GD, so the decoded
        // image is already free of profiles anyway.
        return $image;
    }
}
