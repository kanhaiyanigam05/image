<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Drivers\Gd\Decoders;

use Exception;
use GdImage;
use Kanhaiyanigam05\Gif\Decoder as GifDecoder;
use Kanhaiyanigam05\Gif\Splitter as GifSplitter;
use Kanhaiyanigam05\Image\Drivers\Gd\Core;
use Kanhaiyanigam05\Image\Drivers\Gd\Frame;
use Kanhaiyanigam05\Image\Exceptions\DecoderException;
use Kanhaiyanigam05\Image\Exceptions\RuntimeException;
use Kanhaiyanigam05\Image\Image;
use Kanhaiyanigam05\Image\Interfaces\ColorInterface;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;

class NativeObjectDecoder extends AbstractDecoder
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

        if (!($input instanceof GdImage)) {
            throw new DecoderException('Unable to decode input');
        }

        if (!imageistruecolor($input)) {
            imagepalettetotruecolor($input);
        }

        imagesavealpha($input, true);

        // build image instance
        return new Image(
            $this->driver(),
            new Core([
                new Frame($input)
            ])
        );
    }

    /**
     * Decode image from given GIF source which can be either a file path or binary data
     *
     * Depending on the configuration, this is taken over by the native GD function
     * or, if animations are required, by our own extended decoder.
     *
     * @throws RuntimeException
     */
    protected function decodeGif(mixed $input): ImageInterface
    {
        // create non-animated image depending on config
        if (!$this->driver()->config()->decodeAnimation) {
            $native = match (true) {
                $this->isGifFormat($input) => @imagecreatefromstring($input),
                default => @imagecreatefromgif($input),
            };

            if ($native === false) {
                throw new DecoderException('Unable to decode input.');
            }

            $image = self::decode($native);
            $image->origin()->setMediaType('image/gif');

            return $image;
        }

        try {
            // create empty core
            $core = new Core();

            $gif = GifDecoder::decode($input);
            $splitter = GifSplitter::create($gif)->split();
            $delays = $splitter->getDelays();

            // set loops on core
            if ($loops = $gif->getMainApplicationExtension()?->getLoops()) {
                $core->setLoops($loops);
            }

            // add GDImage instances to core
            foreach ($splitter->coalesceToResources() as $key => $native) {
                $core->push(
                    new Frame($native, $delays[$key] / 100)
                );
            }
        } catch (Exception $e) {
            throw new DecoderException($e->getMessage(), $e->getCode(), $e);
        }

        // create (possibly) animated image
        $image = new Image($this->driver(), $core);

        // set media type
        $image->origin()->setMediaType('image/gif');

        return $image;
    }
}
