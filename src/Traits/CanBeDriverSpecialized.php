<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Traits;

use Kanhaiyanigam05\Image\Exceptions\DriverException;
use Kanhaiyanigam05\Image\Interfaces\DriverInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializableInterface;
use ReflectionClass;

trait CanBeDriverSpecialized
{
    /**
     * The driver with which the instance may be specialized
     */
    protected DriverInterface $driver;

    /**
     * {@inheritdoc}
     *
     * @see SpecializableInterface::specializable()
     */
    public function specializable(): array
    {
        $specializable = [];

        $reflectionClass = new ReflectionClass($this::class);
        if ($constructor = $reflectionClass->getConstructor()) {
            foreach ($constructor->getParameters() as $parameter) {
                $specializable[$parameter->getName()] = $this->{$parameter->getName()};
            }
        }

        return $specializable;
    }

    /**
     * {@inheritdoc}
     *
     * @see SpecializableInterface::driver()
     */
    public function driver(): DriverInterface
    {
        return $this->driver;
    }

    /**
     * {@inheritdoc}
     *
     * @see SpecializableInterface::setDriver()
     */
    public function setDriver(DriverInterface $driver): SpecializableInterface
    {
        if (!$this->belongsToDriver($driver)) {
            throw new DriverException(
                "Class '" . $this::class . "' can not be used with " . $driver->id() . " driver."
            );
        }

        $this->driver = $driver;

        return $this;
    }

    /**
     * Determine if the given object belongs to the driver's namespace
     */
    protected function belongsToDriver(object $object): bool
    {
        $driverId = function (object $object): string|bool {
            $id = substr($object::class, 27);
            return strstr($id, "\\", true);
        };

        return $driverId($this) === $driverId($object);
    }
}
