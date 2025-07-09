<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Gd;

use Kanhaiyanigam05\Image\Drivers\AbstractDriver;
use Kanhaiyanigam05\Image\Exceptions\DriverException;
use Kanhaiyanigam05\Image\Exceptions\RuntimeException;
use Kanhaiyanigam05\Image\Format;
use Kanhaiyanigam05\Image\FileExtension;
use Kanhaiyanigam05\Image\Image;
use Kanhaiyanigam05\Image\Interfaces\ColorProcessorInterface;
use Kanhaiyanigam05\Image\Interfaces\ColorspaceInterface;
use Kanhaiyanigam05\Image\Interfaces\DriverInterface;
use Kanhaiyanigam05\Image\Interfaces\FontProcessorInterface;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\MediaType;

class Driver extends AbstractDriver
{
    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::id()
     */
    public function id(): string
    {
        return 'GD';
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::checkHealth()
     *
     * @codeCoverageIgnore
     */
    public function checkHealth(): void
    {
        if (!extension_loaded('gd') || !function_exists('gd_info')) {
            throw new DriverException(
                'GD PHP extension must be installed to use this driver.'
            );
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::createImage()
     */
    public function createImage(int $width, int $height): ImageInterface
    {
        // build new transparent GDImage
        $data = imagecreatetruecolor($width, $height);
        imagesavealpha($data, true);
        $background = imagecolorallocatealpha($data, 255, 255, 255, 127);
        imagealphablending($data, false);
        imagefill($data, 0, 0, $background);
        imagecolortransparent($data, $background);

        return new Image(
            $this,
            new Core([
                new Frame($data)
            ])
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::createAnimation()
     *
     * @throws RuntimeException
     */
    public function createAnimation(callable $init): ImageInterface
    {
        $animation = new class ($this)
        {
            public function __construct(
                protected DriverInterface $driver,
                public Core $core = new Core()
            ) {
                //
            }

            /**
             * @throws RuntimeException
             */
            public function add(mixed $source, float $delay = 1): self
            {
                $this->core->add(
                    $this->driver->handleInput($source)->core()->first()->setDelay($delay)
                );

                return $this;
            }

            /**
             * @throws RuntimeException
             */
            public function __invoke(): ImageInterface
            {
                return new Image(
                    $this->driver,
                    $this->core
                );
            }
        };

        $init($animation);

        return call_user_func($animation);
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::colorProcessor()
     */
    public function colorProcessor(ColorspaceInterface $colorspace): ColorProcessorInterface
    {
        return new ColorProcessor($colorspace);
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::fontProcessor()
     */
    public function fontProcessor(): FontProcessorInterface
    {
        return new FontProcessor();
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::supports()
     */
    public function supports(string|Format|FileExtension|MediaType $identifier): bool
    {
        return match (Format::tryCreate($identifier)) {
            Format::JPEG => boolval(imagetypes() & IMG_JPEG),
            Format::WEBP => boolval(imagetypes() & IMG_WEBP),
            Format::GIF => boolval(imagetypes() & IMG_GIF),
            Format::PNG => boolval(imagetypes() & IMG_PNG),
            Format::AVIF => boolval(imagetypes() & IMG_AVIF),
            Format::BMP => boolval(imagetypes() & IMG_BMP),
            default => false,
        };
    }

    /**
     * Return version of GD library
     */
    public static function version(): string
    {
        return gd_info()['GD Version'];
    }
}
