<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Imagick\Analyzers;

use Kanhaiyanigam05\Image\Analyzers\ProfileAnalyzer as GenericProfileAnalyzer;
use Kanhaiyanigam05\Image\Colors\Profile;
use Kanhaiyanigam05\Image\Exceptions\ColorException;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;

class ProfileAnalyzer extends GenericProfileAnalyzer implements SpecializedInterface
{
    public function analyze(ImageInterface $image): mixed
    {
        $profiles = $image->core()->native()->getImageProfiles('icc');

        if (!array_key_exists('icc', $profiles)) {
            throw new ColorException('No ICC profile found in image.');
        }

        return new Profile($profiles['icc']);
    }
}
