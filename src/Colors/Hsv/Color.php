<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Colors\Hsv;

use Kanhaiyanigam05\Image\Colors\AbstractColor;
use Kanhaiyanigam05\Image\Colors\Hsv\Channels\Hue;
use Kanhaiyanigam05\Image\Colors\Hsv\Channels\Saturation;
use Kanhaiyanigam05\Image\Colors\Hsv\Channels\Value;
use Kanhaiyanigam05\Image\Colors\Rgb\Colorspace as RgbColorspace;
use Kanhaiyanigam05\Image\InputHandler;
use Kanhaiyanigam05\Image\Interfaces\ColorChannelInterface;
use Kanhaiyanigam05\Image\Interfaces\ColorInterface;
use Kanhaiyanigam05\Image\Interfaces\ColorspaceInterface;

class Color extends AbstractColor
{
    /**
     * Create new color object
     *
     * @return void
     */
    public function __construct(int $h, int $s, int $v)
    {
        /** @throws void */
        $this->channels = [
            new Hue($h),
            new Saturation($s),
            new Value($v),
        ];
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
     * @see ColorInterface::create()
     */
    public static function create(mixed $input): ColorInterface
    {
        return InputHandler::withDecoders([
            Decoders\StringColorDecoder::class,
        ])->handle($input);
    }

    /**
     * Return the Hue channel
     */
    public function hue(): ColorChannelInterface
    {
        /** @throws void */
        return $this->channel(Hue::class);
    }

    /**
     * Return the Saturation channel
     */
    public function saturation(): ColorChannelInterface
    {
        /** @throws void */
        return $this->channel(Saturation::class);
    }

    /**
     * Return the Value channel
     */
    public function value(): ColorChannelInterface
    {
        /** @throws void */
        return $this->channel(Value::class);
    }

    public function toHex(string $prefix = ''): string
    {
        return $this->convertTo(RgbColorspace::class)->toHex($prefix);
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::toString()
     */
    public function toString(): string
    {
        return sprintf(
            'hsv(%d, %d%%, %d%%)',
            $this->hue()->value(),
            $this->saturation()->value(),
            $this->value()->value()
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::isGreyscale()
     */
    public function isGreyscale(): bool
    {
        return $this->saturation()->value() == 0;
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
