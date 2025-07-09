<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Modifiers;

use Kanhaiyanigam05\Image\Geometry\Bezier;
use Kanhaiyanigam05\Image\Interfaces\DrawableInterface;

class DrawBezierModifier extends AbstractDrawModifier
{
    /**
     * Create new modifier object
     *
     * @return void
     */
    public function __construct(public Bezier $drawable)
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
