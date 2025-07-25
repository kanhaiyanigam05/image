<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Interfaces;

use Kanhaiyanigam05\Image\Exceptions\DriverException;

interface SpecializableInterface
{
    /**
     * Return an array of constructor parameters, which is usually passed from
     * the generic object to the specialized object
     *
     * @return array<string, mixed>
     */
    public function specializable(): array;

    /**
     * Set the driver for which the object is specialized
     *
     * @throws DriverException
     */
    public function setDriver(DriverInterface $driver): self;

    /**
     * Return the driver for which the object was specialized
     */
    public function driver(): DriverInterface;
}
