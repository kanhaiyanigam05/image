<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Interfaces;

interface ProfileInterface
{
    /**
     * Cast color profile object to string
     */
    public function __toString(): string;
}
