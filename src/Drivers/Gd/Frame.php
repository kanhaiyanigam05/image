<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Gd;

use GdImage;
use Kanhaiyanigam05\Image\Drivers\AbstractFrame;
use Kanhaiyanigam05\Image\Exceptions\ColorException;
use Kanhaiyanigam05\Image\Exceptions\InputException;
use Kanhaiyanigam05\Image\Geometry\Rectangle;
use Kanhaiyanigam05\Image\Image;
use Kanhaiyanigam05\Image\Interfaces\DriverInterface;
use Kanhaiyanigam05\Image\Interfaces\FrameInterface;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SizeInterface;

class Frame extends AbstractFrame implements FrameInterface
{
    /**
     * Create new frame instance
     *
     * @return void
     */
    public function __construct(
        protected GdImage $native,
        protected float $delay = 0,
        protected int $dispose = 1,
        protected int $offset_left = 0,
        protected int $offset_top = 0
    ) {
        //
    }

    /**
     * {@inheritdoc}
     *
     * @see FrameInterface::toImage()
     */
    public function toImage(DriverInterface $driver): ImageInterface
    {
        return new Image($driver, new Core([$this]));
    }

    /**
     * {@inheritdoc}
     *
     * @see FrameInterface::setNative()
     */
    public function setNative(mixed $native): FrameInterface
    {
        $this->native = $native;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see FrameInterface::native()
     */
    public function native(): GdImage
    {
        return $this->native;
    }

    /**
     * {@inheritdoc}
     *
     * @see FrameInterface::size()
     */
    public function size(): SizeInterface
    {
        return new Rectangle(imagesx($this->native), imagesy($this->native));
    }

    /**
     * {@inheritdoc}
     *
     * @see FrameInterface::delay()
     */
    public function delay(): float
    {
        return $this->delay;
    }

    /**
     * {@inheritdoc}
     *
     * @see FrameInterface::setDelay()
     */
    public function setDelay(float $delay): FrameInterface
    {
        $this->delay = $delay;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see FrameInterface::dispose()
     */
    public function dispose(): int
    {
        return $this->dispose;
    }

    /**
     * {@inheritdoc}
     *
     * @see FrameInterface::setDispose()
     *
     * @throws InputException
     */
    public function setDispose(int $dispose): FrameInterface
    {
        if (!in_array($dispose, [0, 1, 2, 3])) {
            throw new InputException('Value for argument $dispose must be 0, 1, 2 or 3.');
        }

        $this->dispose = $dispose;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see FrameInterface::setOffset()
     */
    public function setOffset(int $left, int $top): FrameInterface
    {
        $this->offset_left = $left;
        $this->offset_top = $top;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see FrameInterface::offsetLeft()
     */
    public function offsetLeft(): int
    {
        return $this->offset_left;
    }

    /**
     * {@inheritdoc}
     *
     * @see FrameInterface::setOffsetLeft()
     */
    public function setOffsetLeft(int $offset): FrameInterface
    {
        $this->offset_left = $offset;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see FrameInterface::offsetTop()
     */
    public function offsetTop(): int
    {
        return $this->offset_top;
    }

    /**
     * {@inheritdoc}
     *
     * @see FrameInterface::setOffsetTop()
     */
    public function setOffsetTop(int $offset): FrameInterface
    {
        $this->offset_top = $offset;

        return $this;
    }

    /**
     * This workaround helps cloning GdImages which is currently not possible.
     *
     * @throws ColorException
     */
    public function __clone(): void
    {
        $this->native = Cloner::clone($this->native);
    }
}
