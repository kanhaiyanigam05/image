<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Gd\Modifiers;

use Kanhaiyanigam05\Image\Exceptions\AnimationException;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;
use Kanhaiyanigam05\Image\Modifiers\SliceAnimationModifier as GenericSliceAnimationModifier;

class SliceAnimationModifier extends GenericSliceAnimationModifier implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        if ($this->offset >= $image->count()) {
            throw new AnimationException('Offset is not in the range of frames.');
        }

        $image->core()->slice($this->offset, $this->length);

        return $image;
    }
}
