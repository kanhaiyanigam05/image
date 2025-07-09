<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Modifiers;

use Kanhaiyanigam05\Image\Drivers\SpecializableModifier;
use Kanhaiyanigam05\Image\Exceptions\RuntimeException;
use Kanhaiyanigam05\Image\Geometry\Rectangle;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SizeInterface;

class CoverModifier extends SpecializableModifier
{
    /**
     * Create new modifier object
     *
     * @return void
     */
    public function __construct(
        public int $width,
        public int $height,
        public string $position = 'center'
    ) {
        //
    }

    /**
     * @throws RuntimeException
     */
    public function getCropSize(ImageInterface $image): SizeInterface
    {
        $imagesize = $image->size();
        $crop = new Rectangle($this->width, $this->height);

        return $crop->contain(
            $imagesize->width(),
            $imagesize->height()
        )->alignPivotTo($imagesize, $this->position);
    }

    /**
     * @throws RuntimeException
     */
    public function getResizeSize(SizeInterface $size): SizeInterface
    {
        return $size->resize($this->width, $this->height);
    }
}
