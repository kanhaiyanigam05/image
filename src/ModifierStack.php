<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image;

use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\ModifierInterface;

class ModifierStack implements ModifierInterface
{
    /**
     * Create new modifier stack object with an array of modifier objects
     *
     * @param array<ModifierInterface> $modifiers
     * @return void
     */
    public function __construct(protected array $modifiers)
    {
        //
    }

    /**
     * Apply all modifiers in stack to the given image
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($this->modifiers as $modifier) {
            $modifier->apply($image);
        }

        return $image;
    }

    /**
     * Append new modifier to the stack
     */
    public function push(ModifierInterface $modifier): self
    {
        $this->modifiers[] = $modifier;

        return $this;
    }
}
