<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Imagick\Modifiers;

use Kanhaiyanigam05\Image\Exceptions\RuntimeException;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SizeInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;
use Kanhaiyanigam05\Image\Modifiers\ResizeModifier as GenericResizeModifier;

class ResizeModifier extends GenericResizeModifier implements SpecializedInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $resizeTo = $this->getAdjustedSize($image);

        foreach ($image as $frame) {
            $frame->native()->scaleImage(
                $resizeTo->width(),
                $resizeTo->height()
            );
        }

        return $image;
    }

    /**
     * @throws RuntimeException
     */
    protected function getAdjustedSize(ImageInterface $image): SizeInterface
    {
        return $image->size()->resize($this->width, $this->height);
    }
}
