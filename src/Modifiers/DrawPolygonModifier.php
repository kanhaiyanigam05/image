<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Modifiers;

use Kanhaiyanigam05\Image\Geometry\Polygon;
use Kanhaiyanigam05\Image\Interfaces\DrawableInterface;

class DrawPolygonModifier extends AbstractDrawModifier
{
    public function __construct(public Polygon $drawable)
    {
        //
    }

    public function drawable(): DrawableInterface
    {
        return $this->drawable;
    }
}
