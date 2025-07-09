<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Modifiers;

use Kanhaiyanigam05\Image\Interfaces\ColorspaceInterface;
use Kanhaiyanigam05\Image\Colors\Cmyk\Colorspace as CmykColorspace;
use Kanhaiyanigam05\Image\Colors\Rgb\Colorspace as RgbColorspace;
use Kanhaiyanigam05\Image\Drivers\SpecializableModifier;
use Kanhaiyanigam05\Image\Exceptions\NotSupportedException;

class ColorspaceModifier extends SpecializableModifier
{
    public function __construct(public string|ColorspaceInterface $target)
    {
        //
    }

    /**
     * @throws NotSupportedException
     */
    public function targetColorspace(): ColorspaceInterface
    {
        if (is_object($this->target)) {
            return $this->target;
        }

        if (in_array($this->target, ['rgb', 'RGB', RgbColorspace::class])) {
            return new RgbColorspace();
        }

        if (in_array($this->target, ['cmyk', 'CMYK', CmykColorspace::class])) {
            return new CmykColorspace();
        }

        throw new NotSupportedException('Given colorspace is not supported.');
    }
}
