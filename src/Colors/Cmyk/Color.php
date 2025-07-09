<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Colors\Cmyk;

use Kanhaiyanigam05\Image\Colors\AbstractColor;
use Kanhaiyanigam05\Image\Colors\Cmyk\Channels\Cyan;
use Kanhaiyanigam05\Image\Colors\Cmyk\Channels\Magenta;
use Kanhaiyanigam05\Image\Colors\Cmyk\Channels\Yellow;
use Kanhaiyanigam05\Image\Colors\Cmyk\Channels\Key;
use Kanhaiyanigam05\Image\Colors\Rgb\Colorspace as RgbColorspace;
use Kanhaiyanigam05\Image\InputHandler;
use Kanhaiyanigam05\Image\Interfaces\ColorChannelInterface;
use Kanhaiyanigam05\Image\Interfaces\ColorInterface;
use Kanhaiyanigam05\Image\Interfaces\ColorspaceInterface;

class Color extends AbstractColor
{
    /**
     * Create new instance
     *
     * @return void
     */
    public function __construct(int $c, int $m, int $y, int $k)
    {
        /** @throws void */
        $this->channels = [
            new Cyan($c),
            new Magenta($m),
            new Yellow($y),
            new Key($k),
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::create()
     */
    public static function create(mixed $input): ColorInterface
    {
        return InputHandler::withDecoders([
            Decoders\StringColorDecoder::class,
        ])->handle($input);
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::colorspace()
     */
    public function colorspace(): ColorspaceInterface
    {
        return new Colorspace();
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::toHex()
     */
    public function toHex(string $prefix = ''): string
    {
        return $this->convertTo(RgbColorspace::class)->toHex($prefix);
    }

    /**
     * Return the CMYK cyan channel
     */
    public function cyan(): ColorChannelInterface
    {
        /** @throws void */
        return $this->channel(Cyan::class);
    }

    /**
     * Return the CMYK magenta channel
     */
    public function magenta(): ColorChannelInterface
    {
        /** @throws void */
        return $this->channel(Magenta::class);
    }

    /**
     * Return the CMYK yellow channel
     */
    public function yellow(): ColorChannelInterface
    {
        /** @throws void */
        return $this->channel(Yellow::class);
    }

    /**
     * Return the CMYK key channel
     */
    public function key(): ColorChannelInterface
    {
        /** @throws void */
        return $this->channel(Key::class);
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::toString()
     */
    public function toString(): string
    {
        return sprintf(
            'cmyk(%d%%, %d%%, %d%%, %d%%)',
            $this->cyan()->value(),
            $this->magenta()->value(),
            $this->yellow()->value(),
            $this->key()->value()
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::isGreyscale()
     */
    public function isGreyscale(): bool
    {
        return 0 === array_sum([
            $this->cyan()->value(),
            $this->magenta()->value(),
            $this->yellow()->value(),
        ]);
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::isTransparent()
     */
    public function isTransparent(): bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::isClear()
     */
    public function isClear(): bool
    {
        return false;
    }
}
