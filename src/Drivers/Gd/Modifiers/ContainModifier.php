<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Gd\Modifiers;

use Kanhaiyanigam05\Image\Colors\Rgb\Channels\Blue;
use Kanhaiyanigam05\Image\Colors\Rgb\Channels\Green;
use Kanhaiyanigam05\Image\Colors\Rgb\Channels\Red;
use Kanhaiyanigam05\Image\Drivers\Gd\Cloner;
use Kanhaiyanigam05\Image\Exceptions\ColorException;
use Kanhaiyanigam05\Image\Interfaces\ColorInterface;
use Kanhaiyanigam05\Image\Interfaces\FrameInterface;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SizeInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;
use Kanhaiyanigam05\Image\Modifiers\ContainModifier as GenericContainModifier;

class ContainModifier extends GenericContainModifier implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $crop = $this->getCropSize($image);
        $resize = $this->getResizeSize($image);
        $background = $this->driver()->handleInput($this->background);
        $blendingColor = $this->driver()->handleInput(
            $this->driver()->config()->blendingColor
        );

        foreach ($image as $frame) {
            $this->modify($frame, $crop, $resize, $background, $blendingColor);
        }

        return $image;
    }

    /**
     * @throws ColorException
     */
    protected function modify(
        FrameInterface $frame,
        SizeInterface $crop,
        SizeInterface $resize,
        ColorInterface $background,
        ColorInterface $blendingColor
    ): void {
        // create new gd image
        $modified = Cloner::cloneEmpty($frame->native(), $resize, $background);

        // make image area transparent to keep transparency
        // even if background-color is set
        $transparent = imagecolorallocatealpha(
            $modified,
            $blendingColor->channel(Red::class)->value(),
            $blendingColor->channel(Green::class)->value(),
            $blendingColor->channel(Blue::class)->value(),
            127,
        );
        imagealphablending($modified, false); // do not blend / just overwrite
        imagecolortransparent($modified, $transparent);
        imagefilledrectangle(
            $modified,
            $crop->pivot()->x(),
            $crop->pivot()->y(),
            $crop->pivot()->x() + $crop->width() - 1,
            $crop->pivot()->y() + $crop->height() - 1,
            $transparent
        );

        // copy image from original with blending alpha
        imagealphablending($modified, true);
        imagecopyresampled(
            $modified,
            $frame->native(),
            $crop->pivot()->x(),
            $crop->pivot()->y(),
            0,
            0,
            $crop->width(),
            $crop->height(),
            $frame->size()->width(),
            $frame->size()->height()
        );

        // set new content as resource
        $frame->setNative($modified);
    }
}
