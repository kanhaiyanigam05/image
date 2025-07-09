<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image;

use Kanhaiyanigam05\Image\Colors\Cmyk\Decoders\StringColorDecoder as CmykStringColorDecoder;
use Kanhaiyanigam05\Image\Colors\Hsl\Decoders\StringColorDecoder as HslStringColorDecoder;
use Kanhaiyanigam05\Image\Colors\Hsv\Decoders\StringColorDecoder as HsvStringColorDecoder;
use Kanhaiyanigam05\Image\Colors\Rgb\Decoders\HexColorDecoder as RgbHexColorDecoder;
use Kanhaiyanigam05\Image\Colors\Rgb\Decoders\HtmlColornameDecoder;
use Kanhaiyanigam05\Image\Colors\Rgb\Decoders\StringColorDecoder as RgbStringColorDecoder;
use Kanhaiyanigam05\Image\Colors\Rgb\Decoders\TransparentColorDecoder;
use Kanhaiyanigam05\Image\Decoders\Base64ImageDecoder;
use Kanhaiyanigam05\Image\Decoders\BinaryImageDecoder;
use Kanhaiyanigam05\Image\Decoders\ColorObjectDecoder;
use Kanhaiyanigam05\Image\Decoders\DataUriImageDecoder;
use Kanhaiyanigam05\Image\Decoders\EncodedImageObjectDecoder;
use Kanhaiyanigam05\Image\Decoders\FilePathImageDecoder;
use Kanhaiyanigam05\Image\Decoders\FilePointerImageDecoder;
use Kanhaiyanigam05\Image\Decoders\ImageObjectDecoder;
use Kanhaiyanigam05\Image\Decoders\NativeObjectDecoder;
use Kanhaiyanigam05\Image\Decoders\SplFileInfoImageDecoder;
use Kanhaiyanigam05\Image\Exceptions\DecoderException;
use Kanhaiyanigam05\Image\Exceptions\DriverException;
use Kanhaiyanigam05\Image\Exceptions\NotSupportedException;
use Kanhaiyanigam05\Image\Interfaces\ColorInterface;
use Kanhaiyanigam05\Image\Interfaces\DecoderInterface;
use Kanhaiyanigam05\Image\Interfaces\DriverInterface;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\InputHandlerInterface;

class InputHandler implements InputHandlerInterface
{
    /**
     * Decoder classnames in hierarchical order
     *
     * @var array<string|DecoderInterface>
     */
    protected array $decoders = [
        NativeObjectDecoder::class,
        ImageObjectDecoder::class,
        ColorObjectDecoder::class,
        RgbHexColorDecoder::class,
        RgbStringColorDecoder::class,
        CmykStringColorDecoder::class,
        HsvStringColorDecoder::class,
        HslStringColorDecoder::class,
        TransparentColorDecoder::class,
        HtmlColornameDecoder::class,
        FilePointerImageDecoder::class,
        FilePathImageDecoder::class,
        SplFileInfoImageDecoder::class,
        BinaryImageDecoder::class,
        DataUriImageDecoder::class,
        Base64ImageDecoder::class,
        EncodedImageObjectDecoder::class,
    ];

    /**
     * Driver with which the decoder classes are specialized
     */
    protected ?DriverInterface $driver = null;

    /**
     * Create new input handler instance with given decoder classnames
     *
     * @param array<string|DecoderInterface> $decoders
     * @return void
     */
    public function __construct(array $decoders = [], ?DriverInterface $driver = null)
    {
        $this->decoders = count($decoders) ? $decoders : $this->decoders;
        $this->driver = $driver;
    }

    /**
     * Static factory method
     *
     * @param array<string|DecoderInterface> $decoders
     */
    public static function withDecoders(array $decoders, ?DriverInterface $driver = null): self
    {
        return new self($decoders, $driver);
    }

    /**
     * {@inheritdoc}
     *
     * @see InputHandlerInterface::handle()
     */
    public function handle(mixed $input): ImageInterface|ColorInterface
    {
        foreach ($this->decoders as $decoder) {
            try {
                // decode with driver specialized decoder
                return $this->resolve($decoder)->decode($input);
            } catch (DecoderException | NotSupportedException $e) {
                // try next decoder
            }
        }

        if (isset($e)) {
            throw new ($e::class)($e->getMessage());
        }

        throw new DecoderException('Unable to decode input.');
    }

    /**
     * Resolve the given classname to an decoder object
     *
     * @throws DriverException
     * @throws NotSupportedException
     */
    private function resolve(string|DecoderInterface $decoder): DecoderInterface
    {
        if (($decoder instanceof DecoderInterface) && empty($this->driver)) {
            return $decoder;
        }

        if (($decoder instanceof DecoderInterface) && !empty($this->driver)) {
            return $this->driver->specialize($decoder);
        }

        if (empty($this->driver)) {
            return new $decoder();
        }

        return $this->driver->specialize(new $decoder());
    }
}
