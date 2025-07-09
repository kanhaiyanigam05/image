<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Interfaces;

use Kanhaiyanigam05\Image\Exceptions\RuntimeException;

interface FileInterface
{
    /**
     * Save data in given path in file system
     *
     * @throws RuntimeException
     */
    public function save(string $filepath): void;

    /**
     * Create file pointer from encoded data
     *
     * @return resource
     */
    public function toFilePointer();

    /**
     * Return size in bytes
     */
    public function size(): int;

    /**
     * Turn encoded data into string
     */
    public function toString(): string;

    /**
     * Cast encoded data into string
     */
    public function __toString(): string;
}
