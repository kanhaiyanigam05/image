<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Modifiers;

use Kanhaiyanigam05\Image\Drivers\SpecializableModifier;
use Kanhaiyanigam05\Image\Exceptions\RuntimeException;
use Kanhaiyanigam05\Image\Geometry\Rectangle;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SizeInterface;

class ContainModifier extends SpecializableModifier
{
    public function __construct(
        public int $width,
        public int $height,
        public mixed $background = 'ffffff',
        public string $position = 'center'
    ) {
        //
    }

    /**
     * @throws RuntimeException
     */
    public function getCropSize(ImageInterface $image): SizeInterface
    {
        return $image->size()
            ->contain(
                $this->width,
                $this->height
            )
            ->alignPivotTo(
                $this->getResizeSize($image),
                $this->position
            );
    }

    public function getResizeSize(ImageInterface $image): SizeInterface
    {
        return new Rectangle($this->width, $this->height);
    }
}
