<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Colors\Cmyk\Channels;

use Kanhaiyanigam05\Image\Colors\AbstractColorChannel;

class Cyan extends AbstractColorChannel
{
    public function min(): int
    {
        return 0;
    }

    public function max(): int
    {
        return 100;
    }
}
