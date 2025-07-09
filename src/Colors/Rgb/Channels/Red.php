<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Colors\Rgb\Channels;

use Kanhaiyanigam05\Image\Colors\AbstractColorChannel;

class Red extends AbstractColorChannel
{
    /**
     * {@inheritdoc}
     *
     * @see ColorChannelInterface::min()
     */
    public function min(): int
    {
        return 0;
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorChannelInterface::max()
     */
    public function max(): int
    {
        return 255;
    }
}
