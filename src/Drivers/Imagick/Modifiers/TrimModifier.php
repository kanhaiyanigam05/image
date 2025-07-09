<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Imagick\Modifiers;

use Kanhaiyanigam05\Image\Exceptions\NotSupportedException;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;
use Kanhaiyanigam05\Image\Modifiers\TrimModifier as GenericTrimModifier;

class TrimModifier extends GenericTrimModifier implements SpecializedInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        if ($image->isAnimated()) {
            throw new NotSupportedException('Trim modifier cannot be applied to animated images.');
        }

        $imagick = $image->core()->native();
        $imagick->trimImage(($this->tolerance / 100 * $imagick->getQuantum()) / 1.5);
        $imagick->setImagePage(0, 0, 0, 0);

        return $image;
    }
}
