<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Encoders;

use Error;
use Kanhaiyanigam05\Image\Exceptions\EncoderException;
use Kanhaiyanigam05\Image\FileExtension;
use Kanhaiyanigam05\Image\Interfaces\EncodedImageInterface;
use Kanhaiyanigam05\Image\Interfaces\EncoderInterface;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;

class FileExtensionEncoder extends AutoEncoder
{
    /**
     * Encoder options
     *
     * @var array<string, mixed>
     */
    protected array $options = [];

    /**
     * Create new encoder instance to encode to format of given file extension
     *
     * @param null|string|FileExtension $extension Target file extension for example "png"
     * @return void
     */
    public function __construct(public null|string|FileExtension $extension = null, mixed ...$options)
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
        $extension = is_null($this->extension) ? $image->origin()->fileExtension() : $this->extension;

        return $image->encode(
            $this->encoderByFileExtension(
                $extension
            )
        );
    }

    /**
     * Create matching encoder for given file extension
     *
     * @throws EncoderException
     */
    protected function encoderByFileExtension(null|string|FileExtension $extension): EncoderInterface
    {
        if (empty($extension)) {
            throw new EncoderException('No encoder found for empty file extension.');
        }

        try {
            $extension = is_string($extension) ? FileExtension::from(strtolower($extension)) : $extension;
        } catch (Error) {
            throw new EncoderException('No encoder found for file extension (' . $extension . ').');
        }

        return $extension->format()->encoder(...$this->options);
    }
}
