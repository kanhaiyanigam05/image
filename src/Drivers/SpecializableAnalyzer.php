<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers;

use Kanhaiyanigam05\Image\Interfaces\AnalyzerInterface;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;

abstract class SpecializableAnalyzer extends Specializable implements AnalyzerInterface
{
    /**
     * {@inheritdoc}
     *
     * @see AnalyzerInterface::analyze()
     */
    public function analyze(ImageInterface $image): mixed
    {
        return $image->analyze($this);
    }
}
