<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers;

use Kanhaiyanigam05\Image\Interfaces\FrameInterface;

abstract class AbstractFrame implements FrameInterface
{
    /**
     * Show debug info for the current image
     *
     * @return array<string, mixed>
     */
    public function __debugInfo(): array
    {
        return [
            'delay' => $this->delay(),
            'left' => $this->offsetLeft(),
            'top' => $this->offsetTop(),
            'dispose' => $this->dispose(),
        ];
    }
}
