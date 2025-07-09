<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Gd\Modifiers;

use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;
use Kanhaiyanigam05\Image\Modifiers\RemoveAnimationModifier as GenericRemoveAnimationModifier;

class RemoveAnimationModifier extends GenericRemoveAnimationModifier implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $image->core()->setNative(
            $this->selectedFrame($image)->native()
        );

        return $image;
    }
}
