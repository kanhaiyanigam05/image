<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers;

use Kanhaiyanigam05\Image\Config;
use Kanhaiyanigam05\Image\Exceptions\DriverException;
use Kanhaiyanigam05\Image\Exceptions\NotSupportedException;
use Kanhaiyanigam05\Image\InputHandler;
use Kanhaiyanigam05\Image\Interfaces\AnalyzerInterface;
use Kanhaiyanigam05\Image\Interfaces\ColorInterface;
use Kanhaiyanigam05\Image\Interfaces\DecoderInterface;
use Kanhaiyanigam05\Image\Interfaces\DriverInterface;
use Kanhaiyanigam05\Image\Interfaces\EncoderInterface;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\ModifierInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializableInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;
use ReflectionClass;

abstract class AbstractDriver implements DriverInterface
{
    /**
     * Driver options
     */
    protected Config $config;

    /**
     * @throws DriverException
     * @return void
     */
    public function __construct()
    {
        $this->config = new Config();
        $this->checkHealth();
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::config()
     */
    public function config(): Config
    {
        return $this->config;
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::handleInput()
     */
    public function handleInput(mixed $input, array $decoders = []): ImageInterface|ColorInterface
    {
        return InputHandler::withDecoders($decoders, $this)->handle($input);
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::specialize()
     */
    public function specialize(
        ModifierInterface|AnalyzerInterface|EncoderInterface|DecoderInterface $object
    ): ModifierInterface|AnalyzerInterface|EncoderInterface|DecoderInterface {
        // return object directly if no specializing is possible
        if (!($object instanceof SpecializableInterface)) {
            return $object;
        }

        // return directly and only attach driver if object is already specialized
        if ($object instanceof SpecializedInterface) {
            $object->setDriver($this);

            return $object;
        }

        // resolve classname for specializable object
        $driver_namespace = (new ReflectionClass($this))->getNamespaceName();
        $object_path = substr($object::class, strlen("Kanhaiyanigam05\\Image\\"));
        $specialized_classname = $driver_namespace . "\\" . $object_path;

        if (!class_exists($specialized_classname)) {
            throw new NotSupportedException(
                "Class '" . $object_path . "' is not supported by " . $this->id() . " driver."
            );
        }

        // create a driver specialized object with the specializable properties of generic object
        $specialized = new $specialized_classname(...$object->specializable());

        // attach driver
        return $specialized->setDriver($this);
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::specializeMultiple()
     *
     * @throws NotSupportedException
     * @throws DriverException
     */
    public function specializeMultiple(array $objects): array
    {
        return array_map(
            function (string|object $object): ModifierInterface|AnalyzerInterface|EncoderInterface|DecoderInterface {
                return $this->specialize(
                    match (true) {
                        is_string($object) => new $object(),
                        is_object($object) => $object,
                    }
                );
            },
            $objects
        );
    }
}
