<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Gd\Modifiers;

use Kanhaiyanigam05\Image\Drivers\Gd\Cloner;
use Kanhaiyanigam05\Image\Exceptions\ColorException;
use Kanhaiyanigam05\Image\Exceptions\GeometryException;
use Kanhaiyanigam05\Image\Exceptions\RuntimeException;
use Kanhaiyanigam05\Image\Interfaces\FrameInterface;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SizeInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;
use Kanhaiyanigam05\Image\Modifiers\ResizeModifier as GenericResizeModifier;

class ResizeModifier extends GenericResizeModifier implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $resizeTo = $this->getAdjustedSize($image);
        foreach ($image as $frame) {
            $this->resizeFrame($frame, $resizeTo);
        }

        return $image;
    }

    /**
     * @throws ColorException
     */
    private function resizeFrame(FrameInterface $frame, SizeInterface $resizeTo): void
    {
        // create empty canvas in target size
        $modified = Cloner::cloneEmpty($frame->native(), $resizeTo);

        // copy content from resource
        imagecopyresampled(
            $modified,
            $frame->native(),
            $resizeTo->pivot()->x(),
            $resizeTo->pivot()->y(),
            0,
            0,
            $resizeTo->width(),
            $resizeTo->height(),
            $frame->size()->width(),
            $frame->size()->height()
        );

        // set new content as resource
        $frame->setNative($modified);
    }

    /**
     * Return the size the modifier will resize to
     *
     * @throws RuntimeException
     * @throws GeometryException
     */
    protected function getAdjustedSize(ImageInterface $image): SizeInterface
    {
        return $image->size()->resize($this->width, $this->height);
    }
}
