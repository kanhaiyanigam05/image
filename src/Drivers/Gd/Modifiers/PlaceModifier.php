<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Gd\Modifiers;

use Kanhaiyanigam05\Image\Exceptions\RuntimeException;
use Kanhaiyanigam05\Image\Interfaces\FrameInterface;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\PointInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;
use Kanhaiyanigam05\Image\Modifiers\PlaceModifier as GenericPlaceModifier;

class PlaceModifier extends GenericPlaceModifier implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $watermark = $this->driver()->handleInput($this->element);
        $position = $this->getPosition($image, $watermark);

        foreach ($image as $frame) {
            imagealphablending($frame->native(), true);

            if ($this->opacity === 100) {
                $this->placeOpaque($frame, $watermark, $position);
            } else {
                $this->placeTransparent($frame, $watermark, $position);
            }
        }

        return $image;
    }

    /**
     * Insert watermark with 100% opacity
     *
     * @throws RuntimeException
     */
    private function placeOpaque(FrameInterface $frame, ImageInterface $watermark, PointInterface $position): void
    {
        imagecopy(
            $frame->native(),
            $watermark->core()->native(),
            $position->x(),
            $position->y(),
            0,
            0,
            $watermark->width(),
            $watermark->height()
        );
    }

    /**
     * Insert watermark transparent with current opacity
     *
     * Unfortunately, the original PHP function imagecopymerge does not work reliably.
     * For example, any transparency of the image to be inserted is not applied correctly.
     * For this reason, a new GDImage is created into which the original image is inserted
     * in the first step and the watermark is inserted with 100% opacity in the second
     * step. This combination is then transferred to the original image again with the
     * respective opacity.
     *
     * Please note: Unfortunately, there is still an edge case, when a transparent image
     * is placed on a transparent background, the "double" transparent areas appear opaque!
     *
     * @throws RuntimeException
     */
    private function placeTransparent(FrameInterface $frame, ImageInterface $watermark, PointInterface $position): void
    {
        $cut = imagecreatetruecolor($watermark->width(), $watermark->height());

        imagecopy(
            $cut,
            $frame->native(),
            0,
            0,
            $position->x(),
            $position->y(),
            imagesx($cut),
            imagesy($cut)
        );

        imagecopy(
            $cut,
            $watermark->core()->native(),
            0,
            0,
            0,
            0,
            imagesx($cut),
            imagesy($cut)
        );

        imagecopymerge(
            $frame->native(),
            $cut,
            $position->x(),
            $position->y(),
            0,
            0,
            $watermark->width(),
            $watermark->height(),
            $this->opacity
        );
    }
}
