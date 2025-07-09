<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Imagick\Decoders;

use Imagick;
use Kanhaiyanigam05\Image\Drivers\Imagick\Core;
use Kanhaiyanigam05\Image\Drivers\SpecializableDecoder;
use Kanhaiyanigam05\Image\Exceptions\DecoderException;
use Kanhaiyanigam05\Image\Image;
use Kanhaiyanigam05\Image\Interfaces\ColorInterface;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;
use Kanhaiyanigam05\Image\Modifiers\AlignRotationModifier;
use Kanhaiyanigam05\Image\Modifiers\RemoveAnimationModifier;

class NativeObjectDecoder extends SpecializableDecoder implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::decode()
     */
    public function decode(mixed $input): ImageInterface|ColorInterface
    {
        if (!is_object($input)) {
            throw new DecoderException('Unable to decode input');
        }

        if (!($input instanceof Imagick)) {
            throw new DecoderException('Unable to decode input');
        }

        // For some JPEG formats, the "coalesceImages()" call leads to an image
        // completely filled with background color. The logic behind this is
        // incomprehensible for me; could be an imagick bug.
        if ($input->getImageFormat() !== 'JPEG') {
            $input = $input->coalesceImages();
        }

        // turn images with colorspace 'GRAY' into 'SRGB' to avoid working on
        // greyscale colorspace images as this results images loosing color
        // information when placed into this image.
        if ($input->getImageColorspace() == Imagick::COLORSPACE_GRAY) {
            $input->setImageColorspace(Imagick::COLORSPACE_SRGB);
        }

        // create image object
        $image = new Image(
            $this->driver(),
            new Core($input)
        );

        // discard animation depending on config
        if (!$this->driver()->config()->decodeAnimation) {
            $image->modify(new RemoveAnimationModifier());
        }

        // adjust image rotatation
        if ($this->driver()->config()->autoOrientation) {
            $image->modify(new AlignRotationModifier());
        }

        // set media type on origin
        $image->origin()->setMediaType($input->getImageMimeType());

        return $image;
    }
}
