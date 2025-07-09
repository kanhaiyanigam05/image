<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Modifiers;

use Kanhaiyanigam05\Image\Drivers\SpecializableModifier;
use Kanhaiyanigam05\Image\Exceptions\DecoderException;
use Kanhaiyanigam05\Image\Interfaces\ColorInterface;
use Kanhaiyanigam05\Image\Interfaces\DrawableInterface;
use RuntimeException;

abstract class AbstractDrawModifier extends SpecializableModifier
{
    /**
     * Return the drawable object which will be rendered by the modifier
     */
    abstract public function drawable(): DrawableInterface;

    /**
     * @throws RuntimeException
     */
    public function backgroundColor(): ColorInterface
    {
        try {
            $color = $this->driver()->handleInput($this->drawable()->backgroundColor());
        } catch (DecoderException) {
            return $this->driver()->handleInput('transparent');
        }

        return $color;
    }

    /**
     * @throws RuntimeException
     */
    public function borderColor(): ColorInterface
    {
        try {
            $color = $this->driver()->handleInput($this->drawable()->borderColor());
        } catch (DecoderException) {
            return $this->driver()->handleInput('transparent');
        }

        return $color;
    }
}
