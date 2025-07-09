<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Encoders;

use Error;
use Kanhaiyanigam05\Image\Drivers\AbstractEncoder;
use Kanhaiyanigam05\Image\Exceptions\EncoderException;
use Kanhaiyanigam05\Image\Interfaces\EncodedImageInterface;
use Kanhaiyanigam05\Image\Interfaces\EncoderInterface;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\MediaType;

class MediaTypeEncoder extends AbstractEncoder
{
    /**
     * Encoder options
     *
     * @var array<string, mixed>
     */
    protected array $options = [];

    /**
     * Create new encoder instance
     *
     * @param null|string|MediaType $mediaType Target media type for example "image/jpeg"
     * @return void
     */
    public function __construct(public null|string|MediaType $mediaType = null, mixed ...$options)
    {
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     *
     * @see EncoderInterface::encode()
     */
    public function encode(ImageInterface $image): EncodedImageInterface
    {
        $mediaType = is_null($this->mediaType) ? $image->origin()->mediaType() : $this->mediaType;

        return $image->encode(
            $this->encoderByMediaType($mediaType)
        );
    }

    /**
     * Return new encoder by given media (MIME) type
     *
     * @throws EncoderException
     */
    protected function encoderByMediaType(string|MediaType $mediaType): EncoderInterface
    {
        try {
            $mediaType = is_string($mediaType) ? MediaType::from($mediaType) : $mediaType;
        } catch (Error) {
            throw new EncoderException('No encoder found for media type (' . $mediaType . ').');
        }

        return $mediaType->format()->encoder(...$this->options);
    }
}
