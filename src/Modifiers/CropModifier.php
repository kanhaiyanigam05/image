<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Modifiers;

use Kanhaiyanigam05\Image\Drivers\SpecializableModifier;
use Kanhaiyanigam05\Image\Exceptions\RuntimeException;
use Kanhaiyanigam05\Image\Geometry\Rectangle;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SizeInterface;

class CropModifier extends SpecializableModifier
{
    /**
     * Create new modifier object
     *
     * @return void
     */
    public function __construct(
        public int $width,
        public int $height,
        public int $offset_x = 0,
        public int $offset_y = 0,
        public mixed $background = 'ffffff',
        public string $position = 'top-left'
    ) {
        //
    }

    /**
     * @throws RuntimeException
     */
    public function crop(ImageInterface $image): SizeInterface
    {
        $crop = new Rectangle($this->width, $this->height);
        $crop->align($this->position);

        return $crop->alignPivotTo(
            $image->size(),
            $this->position
        );
    }
}
