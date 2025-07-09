<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Imagick\Modifiers;

use Kanhaiyanigam05\Image\Exceptions\GeometryException;
use Kanhaiyanigam05\Image\Interfaces\SizeInterface;

class CoverDownModifier extends CoverModifier
{
    /**
     * @throws GeometryException
     */
    public function getResizeSize(SizeInterface $size): SizeInterface
    {
        return $size->resizeDown($this->width, $this->height);
    }
}
