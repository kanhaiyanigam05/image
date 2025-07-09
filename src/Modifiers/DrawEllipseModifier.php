<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Modifiers;

use Kanhaiyanigam05\Image\Geometry\Ellipse;
use Kanhaiyanigam05\Image\Interfaces\DrawableInterface;

class DrawEllipseModifier extends AbstractDrawModifier
{
    public function __construct(public Ellipse $drawable)
    {
        //
    }

    public function drawable(): DrawableInterface
    {
        return $this->drawable;
    }
}
