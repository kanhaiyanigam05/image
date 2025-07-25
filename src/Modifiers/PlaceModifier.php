<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Modifiers;

use Kanhaiyanigam05\Image\Drivers\SpecializableModifier;
use Kanhaiyanigam05\Image\Exceptions\RuntimeException;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\PointInterface;

class PlaceModifier extends SpecializableModifier
{
    /**
     * Create new modifier object
     *
     * @return void
     */
    public function __construct(
        public mixed $element,
        public string $position = 'top-left',
        public int $offset_x = 0,
        public int $offset_y = 0,
        public int $opacity = 100
    ) {
        //
    }

    /**
     * @throws RuntimeException
     */
    public function getPosition(ImageInterface $image, ImageInterface $watermark): PointInterface
    {
        $image_size = $image->size()->movePivot(
            $this->position,
            $this->offset_x,
            $this->offset_y
        );

        $watermark_size = $watermark->size()->movePivot(
            $this->position
        );

        return $image_size->relativePositionTo($watermark_size);
    }
}
