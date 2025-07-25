<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Modifiers;

use Kanhaiyanigam05\Image\Drivers\SpecializableModifier;
use Kanhaiyanigam05\Image\Exceptions\InputException;
use Kanhaiyanigam05\Image\Exceptions\RuntimeException;
use Kanhaiyanigam05\Image\Interfaces\FrameInterface;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;

class RemoveAnimationModifier extends SpecializableModifier
{
    public function __construct(public int|string $position = 0)
    {
        //
    }

    /**
     * @throws RuntimeException
     */
    protected function selectedFrame(ImageInterface $image): FrameInterface
    {
        return $image->core()->frame($this->normalizePosition($image));
    }

    /**
     * Return the position of the selected frame as integer
     *
     * @throws InputException
     */
    protected function normalizePosition(ImageInterface $image): int
    {
        if (is_int($this->position)) {
            return $this->position;
        }

        if (is_numeric($this->position)) {
            return (int) $this->position;
        }

        // calculate position from percentage value
        if (preg_match("/^(?P<percent>[0-9]{1,3})%$/", $this->position, $matches) != 1) {
            throw new InputException(
                'Position must be either integer or a percent value as string.'
            );
        }

        $total = count($image);
        $position = intval(round($total / 100 * intval($matches['percent'])));

        return $position == $total ? $position - 1 : $position;
    }
}
