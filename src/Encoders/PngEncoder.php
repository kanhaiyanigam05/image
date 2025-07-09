<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Encoders;

use Kanhaiyanigam05\Image\Drivers\SpecializableEncoder;

class PngEncoder extends SpecializableEncoder
{
    /**
     * Create new encoder object
     *
     * @return void
     */
    public function __construct(public bool $interlaced = false, public bool $indexed = false)
    {
        //
    }
}
