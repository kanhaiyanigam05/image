<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Interfaces;

use Kanhaiyanigam05\Image\Exceptions\RuntimeException;

interface AnalyzerInterface
{
    /**
     * Analyze given image and return the retrieved data
     *
     * @throws RuntimeException
     */
    public function analyze(ImageInterface $image): mixed;
}
