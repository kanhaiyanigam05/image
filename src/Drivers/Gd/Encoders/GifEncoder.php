<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Gd\Encoders;

use Exception;
use Kanhaiyanigam05\Gif\Builder as GifBuilder;
use Kanhaiyanigam05\Image\Drivers\Gd\Cloner;
use Kanhaiyanigam05\Image\EncodedImage;
use Kanhaiyanigam05\Image\Encoders\GifEncoder as GenericGifEncoder;
use Kanhaiyanigam05\Image\Exceptions\EncoderException;
use Kanhaiyanigam05\Image\Exceptions\RuntimeException;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;

class GifEncoder extends GenericGifEncoder implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see EncoderInterface::encode()
     */
    public function encode(ImageInterface $image): EncodedImage
    {
        if ($image->isAnimated()) {
            return $this->encodeAnimated($image);
        }

        $gd = Cloner::clone($image->core()->native());

        return $this->createEncodedImage(function ($pointer) use ($gd): void {
            imageinterlace($gd, $this->interlaced);
            imagegif($gd, $pointer);
        }, 'image/gif');
    }

    /**
     * @throws RuntimeException
     */
    protected function encodeAnimated(ImageInterface $image): EncodedImage
    {
        try {
            $builder = GifBuilder::canvas(
                $image->width(),
                $image->height()
            );

            foreach ($image as $frame) {
                $builder->addFrame(
                    source: $this->encode($frame->toImage($image->driver()))->toFilePointer(),
                    delay: $frame->delay(),
                    interlaced: $this->interlaced
                );
            }

            $builder->setLoops($image->loops());

            return new EncodedImage($builder->encode(), 'image/gif');
        } catch (Exception $e) {
            throw new EncoderException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
