<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Gd\Modifiers;

use Kanhaiyanigam05\Image\Drivers\Gd\Cloner;
use Kanhaiyanigam05\Image\Exceptions\ColorException;
use Kanhaiyanigam05\Image\Interfaces\ColorInterface;
use Kanhaiyanigam05\Image\Interfaces\FrameInterface;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SizeInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;
use Kanhaiyanigam05\Image\Modifiers\CropModifier as GenericCropModifier;

class CropModifier extends GenericCropModifier implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $originalSize = $image->size();
        $crop = $this->crop($image);
        $background = $this->driver()->handleInput($this->background);

        foreach ($image as $frame) {
            $this->cropFrame($frame, $originalSize, $crop, $background);
        }

        return $image;
    }

    /**
     * @throws ColorException
     */
    protected function cropFrame(
        FrameInterface $frame,
        SizeInterface $originalSize,
        SizeInterface $resizeTo,
        ColorInterface $background
    ): void {
        // create new image with transparent background
        $modified = Cloner::cloneEmpty($frame->native(), $resizeTo, $background);

        // define offset
        $offset_x = $resizeTo->pivot()->x() + $this->offset_x;
        $offset_y = $resizeTo->pivot()->y() + $this->offset_y;

        // define target width & height
        $targetWidth = min($resizeTo->width(), $originalSize->width());
        $targetHeight = min($resizeTo->height(), $originalSize->height());
        $targetWidth = $targetWidth < $originalSize->width() ? $targetWidth + $offset_x : $targetWidth;
        $targetHeight = $targetHeight < $originalSize->height() ? $targetHeight + $offset_y : $targetHeight;

        // don't alpha blend for copy operation to keep transparent areas of original image
        imagealphablending($modified, false);

        // copy content from resource
        imagecopyresampled(
            $modified,
            $frame->native(),
            $offset_x * -1,
            $offset_y * -1,
            0,
            0,
            $targetWidth,
            $targetHeight,
            $targetWidth,
            $targetHeight
        );

        // set new content as resource
        $frame->setNative($modified);
    }
}
