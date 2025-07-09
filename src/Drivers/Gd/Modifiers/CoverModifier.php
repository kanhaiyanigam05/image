<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Gd\Modifiers;

use Kanhaiyanigam05\Image\Drivers\Gd\Cloner;
use Kanhaiyanigam05\Image\Exceptions\ColorException;
use Kanhaiyanigam05\Image\Interfaces\FrameInterface;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\SizeInterface;
use Kanhaiyanigam05\Image\Interfaces\SpecializedInterface;
use Kanhaiyanigam05\Image\Modifiers\CoverModifier as GenericCoverModifier;

class CoverModifier extends GenericCoverModifier implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $crop = $this->getCropSize($image);
        $resize = $this->getResizeSize($crop);

        foreach ($image as $frame) {
            $this->modifyFrame($frame, $crop, $resize);
        }

        return $image;
    }

    /**
     * @throws ColorException
     */
    protected function modifyFrame(FrameInterface $frame, SizeInterface $crop, SizeInterface $resize): void
    {
        // create new image
        $modified = Cloner::cloneEmpty($frame->native(), $resize);

        // copy content from resource
        imagecopyresampled(
            $modified,
            $frame->native(),
            0,
            0,
            $crop->pivot()->x(),
            $crop->pivot()->y(),
            $resize->width(),
            $resize->height(),
            $crop->width(),
            $crop->height()
        );

        // set new content as resource
        $frame->setNative($modified);
    }
}
