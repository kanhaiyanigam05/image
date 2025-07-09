<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Encoders;

use Kanhaiyanigam05\Image\Drivers\SpecializableEncoder;

class WebpEncoder extends SpecializableEncoder
{
    /**
     * Create new encoder object
     *
     * @param null|bool $strip Strip EXIF metadata
     * @return void
     */
    public function __construct(
        public int $quality = self::DEFAULT_QUALITY,
        public ?bool $strip = null
    ) {
        //
    }
}
