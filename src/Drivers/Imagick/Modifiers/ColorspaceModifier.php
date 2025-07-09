<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Imagick\Modifiers;

use Imagick;
use Kanhaiyanigam05\Image\Exceptions\NotSupportedException;
use Kanhaiyanigam05\Image\Interfaces\ColorspaceInterface;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Colors\Cmyk\Colorspace as CmykColorspace;
use Kanhaiyanigam05\Image\Colors\Rgb\Colorspace as RgbColorspace;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;
use Kanhaiyanigam05\Image\Modifiers\ColorspaceModifier as GenericColorspaceModifier;

class ColorspaceModifier extends GenericColorspaceModifier implements SpecializedInterface
{
    /**
     * Map own colorspace classname to Imagick classnames
     *
     * @var array<string, int>
     */
    protected static array $mapping = [
        RgbColorspace::class => Imagick::COLORSPACE_SRGB,
        CmykColorspace::class => Imagick::COLORSPACE_CMYK,
    ];

    public function apply(ImageInterface $image): ImageInterface
    {
        $colorspace = $this->targetColorspace();

        $imagick = $image->core()->native();
        $imagick->transformImageColorspace(
            $this->getImagickColorspace($colorspace)
        );

        return $image;
    }

    /**
     * @throws NotSupportedException
     */
    private function getImagickColorspace(ColorspaceInterface $colorspace): int
    {
        if (!array_key_exists($colorspace::class, self::$mapping)) {
            throw new NotSupportedException('Given colorspace is not supported.');
        }

        return self::$mapping[$colorspace::class];
    }
}
