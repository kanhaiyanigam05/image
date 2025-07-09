<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Gd\Modifiers;

use Kanhaiyanigam05\Image\Exceptions\RuntimeException;
use Kanhaiyanigam05\Image\Interfaces\FrameInterface;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;
use Kanhaiyanigam05\Image\Modifiers\FillModifier as GenericFillModifier;

class FillModifier extends GenericFillModifier implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $color = $this->color($image);

        foreach ($image as $frame) {
            if ($this->hasPosition()) {
                $this->floodFillWithColor($frame, $color);
            } else {
                $this->fillAllWithColor($frame, $color);
            }
        }

        return $image;
    }

    /**
     * @throws RuntimeException
     */
    private function color(ImageInterface $image): int
    {
        return $this->driver()->colorProcessor($image->colorspace())->colorToNative(
            $this->driver()->handleInput($this->color)
        );
    }

    private function floodFillWithColor(FrameInterface $frame, int $color): void
    {
        imagefill(
            $frame->native(),
            $this->position->x(),
            $this->position->y(),
            $color
        );
    }

    private function fillAllWithColor(FrameInterface $frame, int $color): void
    {
        imagealphablending($frame->native(), true);
        imagefilledrectangle(
            $frame->native(),
            0,
            0,
            $frame->size()->width() - 1,
            $frame->size()->height() - 1,
            $color
        );
    }
}
