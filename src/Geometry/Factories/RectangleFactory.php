<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Geometry\Factories;

use Closure;
use Kanhaiyanigam05\Image\Geometry\Point;
use Kanhaiyanigam05\Image\Geometry\Rectangle;
use Kanhaiyanigam05\Image\Interfaces\DrawableFactoryInterface;
use Kanhaiyanigam05\Image\Interfaces\DrawableInterface;
use Kanhaiyanigam05\Image\Interfaces\PointInterface;

class RectangleFactory implements DrawableFactoryInterface
{
    protected Rectangle $rectangle;

    /**
     * Create new instance
     *
     * @return void
     */
    public function __construct(
        protected PointInterface $pivot = new Point(),
        null|Closure|Rectangle $init = null,
    ) {
        $this->rectangle = is_a($init, Rectangle::class) ? $init : new Rectangle(0, 0, $pivot);
        $this->rectangle->setPosition($pivot);

        if (is_callable($init)) {
            $init($this);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see DrawableFactoryInterface::init()
     */
    public static function init(null|Closure|DrawableInterface $init = null): self
    {
        return new self(init: $init);
    }

    /**
     * {@inheritdoc}
     *
     * @see DrawableFactoryInterface::create()
     */
    public function create(): DrawableInterface
    {
        return $this->rectangle;
    }

    /**
     * Set the size of the rectangle to be produced
     */
    public function size(int $width, int $height): self
    {
        $this->rectangle->setSize($width, $height);

        return $this;
    }

    /**
     * Set the width of the rectangle to be produced
     */
    public function width(int $width): self
    {
        $this->rectangle->setWidth($width);

        return $this;
    }

    /**
     * Set the height of the rectangle to be produced
     */
    public function height(int $height): self
    {
        $this->rectangle->setHeight($height);

        return $this;
    }

    /**
     * Set the background color of the rectangle to be produced
     */
    public function background(mixed $color): self
    {
        $this->rectangle->setBackgroundColor($color);

        return $this;
    }

    /**
     * Set the border color & border size of the rectangle to be produced
     */
    public function border(mixed $color, int $size = 1): self
    {
        $this->rectangle->setBorder($color, $size);

        return $this;
    }

    /**
     * Produce the rectangle
     */
    public function __invoke(): Rectangle
    {
        return $this->rectangle;
    }
}
