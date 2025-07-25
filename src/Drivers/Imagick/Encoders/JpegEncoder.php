<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Imagick\Encoders;

use Imagick;
use Kanhaiyanigam05\Image\Drivers\Imagick\Modifiers\StripMetaModifier;
use Kanhaiyanigam05\Image\EncodedImage;
use Kanhaiyanigam05\Image\Encoders\JpegEncoder as GenericJpegEncoder;
use Kanhaiyanigam05\Image\Interfaces\EncodedImageInterface;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;

class JpegEncoder extends GenericJpegEncoder implements SpecializedInterface
{
    public function encode(ImageInterface $image): EncodedImageInterface
    {
        $format = 'JPEG';
        $compression = Imagick::COMPRESSION_JPEG;
        $blendingColor = $this->driver()->handleInput(
            $this->driver()->config()->blendingColor
        );

        // resolve blending color because jpeg has no transparency
        $background = $this->driver()
            ->colorProcessor($image->colorspace())
            ->colorToNative($blendingColor);

        // set alpha value to 1 because Imagick renders
        // possible full transparent colors as black
        $background->setColorValue(Imagick::COLOR_ALPHA, 1);

        // strip meta data
        if ($this->strip || (is_null($this->strip) && $this->driver()->config()->strip)) {
            $image->modify(new StripMetaModifier());
        }

        /** @var Imagick $imagick */
        $imagick = $image->core()->native();
        $imagick->setImageBackgroundColor($background);
        $imagick->setBackgroundColor($background);
        $imagick->setFormat($format);
        $imagick->setImageFormat($format);
        $imagick->setCompression($compression);
        $imagick->setImageCompression($compression);
        $imagick->setCompressionQuality($this->quality);
        $imagick->setImageCompressionQuality($this->quality);
        $imagick->setImageAlphaChannel(Imagick::ALPHACHANNEL_REMOVE);

        if ($this->progressive) {
            $imagick->setInterlaceScheme(Imagick::INTERLACE_PLANE);
        }

        return new EncodedImage($imagick->getImagesBlob(), 'image/jpeg');
    }
}
