<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Imagick;

use Imagick;
use ImagickDraw;
use ImagickDrawException;
use ImagickException;
use ImagickPixel;
use Kanhaiyanigam05\Image\Drivers\AbstractFontProcessor;
use Kanhaiyanigam05\Image\Exceptions\FontException;
use Kanhaiyanigam05\Image\Geometry\Rectangle;
use Kanhaiyanigam05\Image\Interfaces\FontInterface;
use Kanhaiyanigam05\Image\Interfaces\SizeInterface;

class FontProcessor extends AbstractFontProcessor
{
    /**
     * {@inheritdoc}
     *
     * @see FontProcessorInterface::boxSize()
     */
    public function boxSize(string $text, FontInterface $font): SizeInterface
    {
        // no text - no box size
        if (mb_strlen($text) === 0) {
            return new Rectangle(0, 0);
        }

        $draw = $this->toImagickDraw($font);
        $dimensions = (new Imagick())->queryFontMetrics($draw, $text);

        return new Rectangle(
            intval(round($dimensions['textWidth'])),
            intval(round($dimensions['ascender'] + $dimensions['descender'])),
        );
    }

    /**
     * Imagick::annotateImage() needs an ImagickDraw object - this method takes
     * the font object as the base and adds an optional passed color to the new
     * ImagickDraw object.
     *
     * @throws FontException
     * @throws ImagickDrawException
     * @throws ImagickException
     */
    public function toImagickDraw(FontInterface $font, ?ImagickPixel $color = null): ImagickDraw
    {
        if (!$font->hasFilename()) {
            throw new FontException('No font file specified.');
        }

        $draw = new ImagickDraw();
        $draw->setStrokeAntialias(true);
        $draw->setTextAntialias(true);
        $draw->setFont($font->filename());
        $draw->setFontSize($this->nativeFontSize($font));
        $draw->setTextAlignment(Imagick::ALIGN_LEFT);

        if ($color instanceof ImagickPixel) {
            $draw->setFillColor($color);
        }

        return $draw;
    }
}
