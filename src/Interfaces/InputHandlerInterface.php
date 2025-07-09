<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Interfaces;

use Kanhaiyanigam05\Image\Exceptions\RuntimeException;

interface InputHandlerInterface
{
    /**
     * Try to decode the given input with each decoder of the the handler chain
     *
     * @throws RuntimeException
     */
    public function handle(mixed $input): ImageInterface|ColorInterface;
}
