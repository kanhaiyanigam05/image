<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Colors;

use Kanhaiyanigam05\Image\File;
use Kanhaiyanigam05\Image\Exceptions\RuntimeException;
use Kanhaiyanigam05\Image\Interfaces\ProfileInterface;

class Profile extends File implements ProfileInterface
{
    /**
     * Create profile object from path in file system
     *
     * @throws RuntimeException
     */
    public static function fromPath(string $path): self
    {
        return new self(fopen($path, 'r'));
    }
}
