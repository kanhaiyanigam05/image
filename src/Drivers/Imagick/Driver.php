<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Imagick;

use Imagick;
use ImagickPixel;
use Kanhaiyanigam05\Image\Drivers\AbstractDriver;
use Kanhaiyanigam05\Image\Exceptions\DriverException;
use Kanhaiyanigam05\Image\Exceptions\NotSupportedException;
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
        return 'Imagick';
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
        if (!extension_loaded('imagick') || !class_exists('Imagick')) {
            throw new DriverException(
                'Imagick PHP extension must be installed to use this driver.'
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
        $background = new ImagickPixel('rgba(255, 255, 255, 0)');

        $imagick = new Imagick();
        $imagick->newImage($width, $height, $background, 'png');
        $imagick->setType(Imagick::IMGTYPE_UNDEFINED);
        $imagick->setImageType(Imagick::IMGTYPE_UNDEFINED);
        $imagick->setColorspace(Imagick::COLORSPACE_SRGB);
        $imagick->setImageResolution(96, 96);
        $imagick->setImageBackgroundColor($background);

        return new Image($this, new Core($imagick));
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
        $imagick = new Imagick();
        $imagick->setFormat('gif');

        $animation = new class ($this, $imagick)
        {
            public function __construct(
                protected DriverInterface $driver,
                public Imagick $imagick
            ) {
                //
            }

            /**
             * @throws RuntimeException
             */
            public function add(mixed $source, float $delay = 1): self
            {
                $native = $this->driver->handleInput($source)->core()->native();
                $native->setImageDelay(intval(round($delay * 100)));

                $this->imagick->addImage($native);

                return $this;
            }

            /**
             * @throws RuntimeException
             */
            public function __invoke(): ImageInterface
            {
                return new Image(
                    $this->driver,
                    new Core($this->imagick)
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
        try {
            $format = Format::create($identifier);
        } catch (NotSupportedException) {
            return false;
        }

        return count(Imagick::queryFormats($format->name)) >= 1;
    }

    /**
     * Return version of ImageMagick library
     *
     * @throws DriverException
     */
    public static function version(): string
    {
        $pattern = '/^ImageMagick (?P<version>(0|[1-9]\d*)\.(0|[1-9]\d*)\.(0|[1-9]\d*)' .
            '(?:-((?:0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*)(?:\.(?:0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*))*))?' .
            '(?:\+([0-9a-zA-Z-]+(?:\.[0-9a-zA-Z-]+)*))?)/';

        if (preg_match($pattern, Imagick::getVersion()['versionString'], $matches) !== 1) {
            throw new DriverException('Unable to read ImageMagick version number.');
        }

        return $matches['version'];
    }
}
