<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Modifiers;

use Kanhaiyanigam05\Image\Geometry\Rectangle;
use Kanhaiyanigam05\Image\Interfaces\DrawableInterface;

class DrawRectangleModifier extends AbstractDrawModifier
{
    /**
     * Create new modifier object
     *
     * @return void
     */
    public function __construct(public Rectangle $drawable)
    {
        //
    }

    /**
     * Return object to be drawn
     */
    public function drawable(): DrawableInterface
    {
        return $this->drawable;
    }
}
