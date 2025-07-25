<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image\Geometry\Tools;

use Kanhaiyanigam05\Image\Exceptions\GeometryException;
use Kanhaiyanigam05\Image\Geometry\Rectangle;
use Kanhaiyanigam05\Image\Interfaces\SizeInterface;

class RectangleResizer
{
    /**
     * @throws GeometryException
     * @return void
     */
    public function __construct(
        protected ?int $width = null,
        protected ?int $height = null,
    ) {
        if (is_int($width) && $width < 1) {
            throw new GeometryException(
                'The width you specify must be greater than or equal to 1.'
            );
        }

        if (is_int($height) && $height < 1) {
            throw new GeometryException(
                'The height you specify must be greater than or equal to 1.'
            );
        }
    }

    /**
     * Static factory method to create resizer with given target size
     *
     * @throws GeometryException
     */
    public static function to(mixed ...$arguments): self
    {
        return new self(...$arguments);
    }

    /**
     * Determine if resize has target width
     */
    protected function hasTargetWidth(): bool
    {
        return is_int($this->width);
    }

    /**
     * Return target width of resizer if available
     */
    protected function getTargetWidth(): ?int
    {
        return $this->hasTargetWidth() ? $this->width : null;
    }

    /**
     * Determine if resize has target height
     */
    protected function hasTargetHeight(): bool
    {
        return is_int($this->height);
    }

    /**
     * Return target width of resizer if available
     */
    protected function getTargetHeight(): ?int
    {
        return $this->hasTargetHeight() ? $this->height : null;
    }

    /**
     * Return target size object
     *
     * @throws GeometryException
     */
    protected function getTargetSize(): SizeInterface
    {
        if (!$this->hasTargetWidth() || !$this->hasTargetHeight()) {
            throw new GeometryException('Target size needs width and height.');
        }

        return new Rectangle($this->width, $this->height);
    }

    /**
     * Set target width of resizer
     */
    public function toWidth(int $width): self
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Set target height of resizer
     */
    public function toHeight(int $height): self
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Set target size to given size object
     */
    public function toSize(SizeInterface $size): self
    {
        $this->width = $size->width();
        $this->height = $size->height();

        return $this;
    }

    /**
     * Get proportinal width
     */
    protected function getProportionalWidth(SizeInterface $size): int
    {
        if (!$this->hasTargetHeight()) {
            return $size->width();
        }

        return max([1, (int) round($this->height * $size->aspectRatio())]);
    }

    /**
     * Get proportinal height
     */
    protected function getProportionalHeight(SizeInterface $size): int
    {
        if (!$this->hasTargetWidth()) {
            return $size->height();
        }

        return max([1, (int) round($this->width / $size->aspectRatio())]);
    }

    /**
     * Resize given size to target size of the resizer
     */
    public function resize(SizeInterface $size): SizeInterface
    {
        $resized = new Rectangle($size->width(), $size->height());

        if ($width = $this->getTargetWidth()) {
            $resized->setWidth($width);
        }

        if ($height = $this->getTargetHeight()) {
            $resized->setHeight($height);
        }

        return $resized;
    }

    /**
     * Resize given size to target size of the resizer but do not exceed original size
     */
    public function resizeDown(SizeInterface $size): SizeInterface
    {
        $resized = new Rectangle($size->width(), $size->height());

        if ($width = $this->getTargetWidth()) {
            $resized->setWidth(
                min($width, $size->width())
            );
        }

        if ($height = $this->getTargetHeight()) {
            $resized->setHeight(
                min($height, $size->height())
            );
        }

        return $resized;
    }

    /**
     * Resize given size to target size proportinally
     */
    public function scale(SizeInterface $size): SizeInterface
    {
        $resized = new Rectangle($size->width(), $size->height());

        if ($this->hasTargetWidth() && $this->hasTargetHeight()) {
            $resized->setWidth(min(
                $this->getProportionalWidth($size),
                $this->getTargetWidth()
            ));
            $resized->setHeight(min(
                $this->getProportionalHeight($size),
                $this->getTargetHeight()
            ));
        } elseif ($this->hasTargetWidth()) {
            $resized->setWidth($this->getTargetWidth());
            $resized->setHeight($this->getProportionalHeight($size));
        } elseif ($this->hasTargetHeight()) {
            $resized->setWidth($this->getProportionalWidth($size));
            $resized->setHeight($this->getTargetHeight());
        }

        return $resized;
    }

    /**
     * Resize given size to target size proportinally but do not exceed original size
     */
    public function scaleDown(SizeInterface $size): SizeInterface
    {
        $resized = new Rectangle($size->width(), $size->height());

        if ($this->hasTargetWidth() && $this->hasTargetHeight()) {
            $resized->setWidth(min(
                $this->getProportionalWidth($size),
                $this->getTargetWidth(),
                $size->width()
            ));
            $resized->setHeight(min(
                $this->getProportionalHeight($size),
                $this->getTargetHeight(),
                $size->height()
            ));
        } elseif ($this->hasTargetWidth()) {
            $resized->setWidth(min(
                $this->getTargetWidth(),
                $size->width()
            ));
            $resized->setHeight(min(
                $this->getProportionalHeight($size),
                $size->height()
            ));
        } elseif ($this->hasTargetHeight()) {
            $resized->setWidth(min(
                $this->getProportionalWidth($size),
                $size->width()
            ));
            $resized->setHeight(min(
                $this->getTargetHeight(),
                $size->height()
            ));
        }

        return $resized;
    }

    /**
     * Scale given size to cover target size
     *
     * @param SizeInterface $size Size to be resized
     * @throws GeometryException
     */
    public function cover(SizeInterface $size): SizeInterface
    {
        $resized = new Rectangle($size->width(), $size->height());

        // auto height
        $resized->setWidth($this->getTargetWidth());
        $resized->setHeight($this->getProportionalHeight($size));

        if ($resized->fitsInto($this->getTargetSize())) {
            // auto width
            $resized->setWidth($this->getProportionalWidth($size));
            $resized->setHeight($this->getTargetHeight());
        }

        return $resized;
    }

    /**
     * Scale given size to contain target size
     *
     * @param SizeInterface $size Size to be resized
     * @throws GeometryException
     */
    public function contain(SizeInterface $size): SizeInterface
    {
        $resized = new Rectangle($size->width(), $size->height());

        // auto height
        $resized->setWidth($this->getTargetWidth());
        $resized->setHeight($this->getProportionalHeight($size));

        if (!$resized->fitsInto($this->getTargetSize())) {
            // auto width
            $resized->setWidth($this->getProportionalWidth($size));
            $resized->setHeight($this->getTargetHeight());
        }

        return $resized;
    }

    /**
     * Scale given size to contain target size but prevent upsizing
     *
     * @param SizeInterface $size Size to be resized
     * @throws GeometryException
     */
    public function containDown(SizeInterface $size): SizeInterface
    {
        $resized = new Rectangle($size->width(), $size->height());

        // auto height
        $resized->setWidth(
            min($size->width(), $this->getTargetWidth())
        );

        $resized->setHeight(
            min($size->height(), $this->getProportionalHeight($size))
        );

        if (!$resized->fitsInto($this->getTargetSize())) {
            // auto width
            $resized->setWidth(
                min($size->width(), $this->getProportionalWidth($size))
            );
            $resized->setHeight(
                min($size->height(), $this->getTargetHeight())
            );
        }

        return $resized;
    }

    /**
     * Crop target size out of given size at given position (i.e. move the pivot point)
     */
    public function crop(SizeInterface $size, string $position = 'top-left'): SizeInterface
    {
        return $this->resize($size)->alignPivotTo(
            $size->movePivot($position),
            $position
        );
    }
}
