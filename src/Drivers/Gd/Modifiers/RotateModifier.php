<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Gd\Modifiers;

use Kanhaiyanigam05\Image\Colors\Rgb\Channels\Blue;
use Kanhaiyanigam05\Image\Colors\Rgb\Channels\Green;
use Kanhaiyanigam05\Image\Colors\Rgb\Channels\Red;
use Kanhaiyanigam05\Image\Drivers\Gd\Cloner;
use Kanhaiyanigam05\Image\Exceptions\ColorException;
use Kanhaiyanigam05\Image\Geometry\Rectangle;
use Kanhaiyanigam05\Image\Interfaces\ColorInterface;
use Kanhaiyanigam05\Image\Interfaces\FrameInterface;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;
use Kanhaiyanigam05\Image\Modifiers\RotateModifier as GenericRotateModifier;

class RotateModifier extends GenericRotateModifier implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $background = $this->driver()->handleInput($this->background);

        foreach ($image as $frame) {
            $this->modifyFrame($frame, $background);
        }

        return $image;
    }

    /**
     * Apply rotation modification on given frame, given background
     * color is used for newly create image areas
     *
     * @throws ColorException
     */
    protected function modifyFrame(FrameInterface $frame, ColorInterface $background): void
    {
        // get transparent color from frame core
        $transparent = match ($transparent = imagecolortransparent($frame->native())) {
            -1 => imagecolorallocatealpha(
                $frame->native(),
                $background->channel(Red::class)->value(),
                $background->channel(Green::class)->value(),
                $background->channel(Blue::class)->value(),
                127
            ),
            default => $transparent,
        };

        // rotate original image against transparent background
        $rotated = imagerotate(
            $frame->native(),
            $this->rotationAngle(),
            $transparent
        );

        // create size from original after rotation
        $container = (new Rectangle(
            imagesx($rotated),
            imagesy($rotated),
        ))->movePivot('center');

        // create size from original and rotate points
        $cutout = (new Rectangle(
            imagesx($frame->native()),
            imagesy($frame->native()),
            $container->pivot()
        ))->align('center')
            ->valign('center')
            ->rotate($this->rotationAngle() * -1);

        // create new gd image
        $modified = Cloner::cloneEmpty($frame->native(), $container, $background);

        // draw the cutout on new gd image to have a transparent
        // background where the rotated image will be placed
        imagealphablending($modified, false);
        imagefilledpolygon(
            $modified,
            $cutout->toArray(),
            imagecolortransparent($modified)
        );

        // place rotated image on new gd image
        imagealphablending($modified, true);
        imagecopy(
            $modified,
            $rotated,
            0,
            0,
            0,
            0,
            imagesx($rotated),
            imagesy($rotated)
        );

        $frame->setNative($modified);
    }
}
