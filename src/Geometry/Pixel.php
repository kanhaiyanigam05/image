<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Geometry;

use Kanhaiyanigam05\Image\Interfaces\ColorInterface;

class Pixel extends Point
{
    /**
     * Create new pixel instance
     *
     * @return void
     */
    public function __construct(
        protected ColorInterface $background,
        protected int $x,
        protected int $y
    ) {
        //
    }

    /**
     * {@inheritdoc}
     *
     * @see DrawableInterface::setBackgroundColor()
     */
    public function setBackgroundColor(ColorInterface $background): self
    {
        $this->background = $background;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see DrawableInterface::backgroundColor()
     */
    public function backgroundColor(): ColorInterface
    {
        return $this->background;
    }
}
