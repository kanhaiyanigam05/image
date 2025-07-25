<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Modifiers;

use Kanhaiyanigam05\Image\Drivers\SpecializableModifier;
use Kanhaiyanigam05\Image\Exceptions\RuntimeException;
use Kanhaiyanigam05\Image\Geometry\Rectangle;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SizeInterface;

class ResizeCanvasModifier extends SpecializableModifier
{
    /**
     * Create new modifier object
     *
     * @return void
     */
    public function __construct(
        public ?int $width = null,
        public ?int $height = null,
        public mixed $background = 'ffffff',
        public string $position = 'center'
    ) {
        //
    }

    /**
     * Build the crop size to be used for the ResizeCanvas process
     *
     * @throws RuntimeException
     */
    protected function cropSize(ImageInterface $image, bool $relative = false): SizeInterface
    {
        $size = match ($relative) {
            true => new Rectangle(
                is_null($this->width) ? $image->width() : $image->width() + $this->width,
                is_null($this->height) ? $image->height() : $image->height() + $this->height,
            ),
            default => new Rectangle(
                is_null($this->width) ? $image->width() : $this->width,
                is_null($this->height) ? $image->height() : $this->height,
            ),
        };

        return $size->alignPivotTo($image->size(), $this->position);
    }
}
