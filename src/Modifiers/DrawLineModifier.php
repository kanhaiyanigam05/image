<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Modifiers;

use Kanhaiyanigam05\Image\Geometry\Line;
use Kanhaiyanigam05\Image\Interfaces\DrawableInterface;

class DrawLineModifier extends AbstractDrawModifier
{
    /**
     * Create new modifier object
     *
     * @return void
     */
    public function __construct(public Line $drawable)
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
