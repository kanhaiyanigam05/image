<?php

declare(strict_types=1);

namespace Kanhaiyanigam05\Image;

use Closure;
use Kanhaiyanigam05\Image\Exceptions\RuntimeException;
use Traversable;
use Kanhaiyanigam05\Image\Analyzers\ColorspaceAnalyzer;
use Kanhaiyanigam05\Image\Analyzers\HeightAnalyzer;
use Kanhaiyanigam05\Image\Analyzers\PixelColorAnalyzer;
use Kanhaiyanigam05\Image\Analyzers\PixelColorsAnalyzer;
use Kanhaiyanigam05\Image\Analyzers\ProfileAnalyzer;
use Kanhaiyanigam05\Image\Analyzers\ResolutionAnalyzer;
use Kanhaiyanigam05\Image\Analyzers\WidthAnalyzer;
use Kanhaiyanigam05\Image\Encoders\AutoEncoder;
use Kanhaiyanigam05\Image\Encoders\AvifEncoder;
use Kanhaiyanigam05\Image\Encoders\BmpEncoder;
use Kanhaiyanigam05\Image\Encoders\FileExtensionEncoder;
use Kanhaiyanigam05\Image\Encoders\FilePathEncoder;
use Kanhaiyanigam05\Image\Encoders\GifEncoder;
use Kanhaiyanigam05\Image\Encoders\HeicEncoder;
use Kanhaiyanigam05\Image\Encoders\Jpeg2000Encoder;
use Kanhaiyanigam05\Image\Encoders\JpegEncoder;
use Kanhaiyanigam05\Image\Encoders\MediaTypeEncoder;
use Kanhaiyanigam05\Image\Encoders\PngEncoder;
use Kanhaiyanigam05\Image\Encoders\TiffEncoder;
use Kanhaiyanigam05\Image\Encoders\WebpEncoder;
use Kanhaiyanigam05\Image\Exceptions\EncoderException;
use Kanhaiyanigam05\Image\Geometry\Bezier;
use Kanhaiyanigam05\Image\Geometry\Circle;
use Kanhaiyanigam05\Image\Geometry\Ellipse;
use Kanhaiyanigam05\Image\Geometry\Factories\BezierFactory;
use Kanhaiyanigam05\Image\Geometry\Factories\CircleFactory;
use Kanhaiyanigam05\Image\Geometry\Factories\EllipseFactory;
use Kanhaiyanigam05\Image\Geometry\Factories\LineFactory;
use Kanhaiyanigam05\Image\Geometry\Factories\PolygonFactory;
use Kanhaiyanigam05\Image\Geometry\Factories\RectangleFactory;
use Kanhaiyanigam05\Image\Geometry\Line;
use Kanhaiyanigam05\Image\Geometry\Point;
use Kanhaiyanigam05\Image\Geometry\Polygon;
use Kanhaiyanigam05\Image\Geometry\Rectangle;
use Kanhaiyanigam05\Image\Interfaces\AnalyzerInterface;
use Kanhaiyanigam05\Image\Interfaces\CollectionInterface;
use Kanhaiyanigam05\Image\Interfaces\ColorInterface;
use Kanhaiyanigam05\Image\Interfaces\ColorspaceInterface;
use Kanhaiyanigam05\Image\Interfaces\CoreInterface;
use Kanhaiyanigam05\Image\Interfaces\DriverInterface;
use Kanhaiyanigam05\Image\Interfaces\EncodedImageInterface;
use Kanhaiyanigam05\Image\Interfaces\EncoderInterface;
use Kanhaiyanigam05\Image\Interfaces\FontInterface;
use Kanhaiyanigam05\Image\Interfaces\FrameInterface;
use Kanhaiyanigam05\Image\Interfaces\ImageInterface;
use Kanhaiyanigam05\Image\Interfaces\ModifierInterface;
use Kanhaiyanigam05\Image\Interfaces\ProfileInterface;
use Kanhaiyanigam05\Image\Interfaces\ResolutionInterface;
use Kanhaiyanigam05\Image\Interfaces\SizeInterface;
use Kanhaiyanigam05\Image\Modifiers\AlignRotationModifier;
use Kanhaiyanigam05\Image\Modifiers\BlendTransparencyModifier;
use Kanhaiyanigam05\Image\Modifiers\BlurModifier;
use Kanhaiyanigam05\Image\Modifiers\BrightnessModifier;
use Kanhaiyanigam05\Image\Modifiers\ColorizeModifier;
use Kanhaiyanigam05\Image\Modifiers\ColorspaceModifier;
use Kanhaiyanigam05\Image\Modifiers\ContainModifier;
use Kanhaiyanigam05\Image\Modifiers\ContrastModifier;
use Kanhaiyanigam05\Image\Modifiers\CropModifier;
use Kanhaiyanigam05\Image\Modifiers\DrawBezierModifier;
use Kanhaiyanigam05\Image\Modifiers\DrawEllipseModifier;
use Kanhaiyanigam05\Image\Modifiers\DrawLineModifier;
use Kanhaiyanigam05\Image\Modifiers\DrawPixelModifier;
use Kanhaiyanigam05\Image\Modifiers\DrawPolygonModifier;
use Kanhaiyanigam05\Image\Modifiers\DrawRectangleModifier;
use Kanhaiyanigam05\Image\Modifiers\FillModifier;
use Kanhaiyanigam05\Image\Modifiers\CoverDownModifier;
use Kanhaiyanigam05\Image\Modifiers\CoverModifier;
use Kanhaiyanigam05\Image\Modifiers\FlipModifier;
use Kanhaiyanigam05\Image\Modifiers\FlopModifier;
use Kanhaiyanigam05\Image\Modifiers\GammaModifier;
use Kanhaiyanigam05\Image\Modifiers\GreyscaleModifier;
use Kanhaiyanigam05\Image\Modifiers\InvertModifier;
use Kanhaiyanigam05\Image\Modifiers\PadModifier;
use Kanhaiyanigam05\Image\Modifiers\PixelateModifier;
use Kanhaiyanigam05\Image\Modifiers\PlaceModifier;
use Kanhaiyanigam05\Image\Modifiers\ProfileModifier;
use Kanhaiyanigam05\Image\Modifiers\ProfileRemovalModifier;
use Kanhaiyanigam05\Image\Modifiers\QuantizeColorsModifier;
use Kanhaiyanigam05\Image\Modifiers\RemoveAnimationModifier;
use Kanhaiyanigam05\Image\Modifiers\ResizeCanvasModifier;
use Kanhaiyanigam05\Image\Modifiers\ResizeCanvasRelativeModifier;
use Kanhaiyanigam05\Image\Modifiers\ResizeDownModifier;
use Kanhaiyanigam05\Image\Modifiers\ResizeModifier;
use Kanhaiyanigam05\Image\Modifiers\ResolutionModifier;
use Kanhaiyanigam05\Image\Modifiers\RotateModifier;
use Kanhaiyanigam05\Image\Modifiers\ScaleDownModifier;
use Kanhaiyanigam05\Image\Modifiers\ScaleModifier;
use Kanhaiyanigam05\Image\Modifiers\SharpenModifier;
use Kanhaiyanigam05\Image\Modifiers\SliceAnimationModifier;
use Kanhaiyanigam05\Image\Modifiers\TextModifier;
use Kanhaiyanigam05\Image\Modifiers\TrimModifier;
use Kanhaiyanigam05\Image\Typography\FontFactory;

final class Image implements ImageInterface
{
    /**
     * The origin from which the image was created
     */
    private Origin $origin;

    /**
     * Create new instance
     *
     * @throws RuntimeException
     * @return void
     */
    public function __construct(
        private DriverInterface $driver,
        private CoreInterface $core,
        private CollectionInterface $exif = new Collection()
    ) {
        $this->origin = new Origin();
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::driver()
     */
    public function driver(): DriverInterface
    {
        return $this->driver;
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::core()
     */
    public function core(): CoreInterface
    {
        return $this->core;
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::origin()
     */
    public function origin(): Origin
    {
        return $this->origin;
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::setOrigin()
     */
    public function setOrigin(Origin $origin): ImageInterface
    {
        $this->origin = $origin;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::count()
     */
    public function count(): int
    {
        return $this->core->count();
    }

    /**
     * Implementation of IteratorAggregate
     *
     * @return Traversable<FrameInterface>
     */
    public function getIterator(): Traversable
    {
        return $this->core;
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::isAnimated()
     */
    public function isAnimated(): bool
    {
        return $this->count() > 1;
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::removeAnimation(
     */
    public function removeAnimation(int|string $position = 0): ImageInterface
    {
        return $this->modify(new RemoveAnimationModifier($position));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::sliceAnimation()
     */
    public function sliceAnimation(int $offset = 0, ?int $length = null): ImageInterface
    {
        return $this->modify(new SliceAnimationModifier($offset, $length));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::loops()
     */
    public function loops(): int
    {
        return $this->core->loops();
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::setLoops()
     */
    public function setLoops(int $loops): ImageInterface
    {
        $this->core->setLoops($loops);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::exif()
     */
    public function exif(?string $query = null): mixed
    {
        return is_null($query) ? $this->exif : $this->exif->get($query);
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::setExif()
     */
    public function setExif(CollectionInterface $exif): ImageInterface
    {
        $this->exif = $exif;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::modify()
     */
    public function modify(ModifierInterface $modifier): ImageInterface
    {
        return $this->driver->specialize($modifier)->apply($this);
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::analyze()
     */
    public function analyze(AnalyzerInterface $analyzer): mixed
    {
        return $this->driver->specialize($analyzer)->analyze($this);
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::encode()
     */
    public function encode(EncoderInterface $encoder = new AutoEncoder()): EncodedImageInterface
    {
        return $this->driver->specialize($encoder)->encode($this);
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::save()
     */
    public function save(?string $path = null, mixed ...$options): ImageInterface
    {
        $path = is_null($path) ? $this->origin()->filePath() : $path;

        if (is_null($path)) {
            throw new EncoderException('Could not determine file path to save.');
        }

        try {
            // try to determine encoding format by file extension of the path
            $encoded = $this->encodeByPath($path, ...$options);
        } catch (EncoderException) {
            // fallback to encoding format by media type
            $encoded = $this->encodeByMediaType(null, ...$options);
        }

        $encoded->save($path);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::width()
     */
    public function width(): int
    {
        return $this->analyze(new WidthAnalyzer());
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::height()
     */
    public function height(): int
    {
        return $this->analyze(new HeightAnalyzer());
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::size()
     */
    public function size(): SizeInterface
    {
        return new Rectangle($this->width(), $this->height());
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::colorspace()
     */
    public function colorspace(): ColorspaceInterface
    {
        return $this->analyze(new ColorspaceAnalyzer());
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::setColorspace()
     */
    public function setColorspace(string|ColorspaceInterface $colorspace): ImageInterface
    {
        return $this->modify(new ColorspaceModifier($colorspace));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::resolution()
     */
    public function resolution(): ResolutionInterface
    {
        return $this->analyze(new ResolutionAnalyzer());
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::setResolution()
     */
    public function setResolution(float $x, float $y): ImageInterface
    {
        return $this->modify(new ResolutionModifier($x, $y));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::pickColor()
     */
    public function pickColor(int $x, int $y, int $frame_key = 0): ColorInterface
    {
        return $this->analyze(new PixelColorAnalyzer($x, $y, $frame_key));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::pickColors()
     */
    public function pickColors(int $x, int $y): CollectionInterface
    {
        return $this->analyze(new PixelColorsAnalyzer($x, $y));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::blendingColor()
     */
    public function blendingColor(): ColorInterface
    {
        return $this->driver()->handleInput(
            $this->driver()->config()->blendingColor
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::setBlendingColor()
     */
    public function setBlendingColor(mixed $color): ImageInterface
    {
        $this->driver()->config()->setOptions(
            blendingColor: $this->driver()->handleInput($color)
        );

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::blendTransparency()
     */
    public function blendTransparency(mixed $color = null): ImageInterface
    {
        return $this->modify(new BlendTransparencyModifier($color));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::profile()
     */
    public function profile(): ProfileInterface
    {
        return $this->analyze(new ProfileAnalyzer());
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::setProfile()
     */
    public function setProfile(ProfileInterface $profile): ImageInterface
    {
        return $this->modify(new ProfileModifier($profile));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::removeProfile()
     */
    public function removeProfile(): ImageInterface
    {
        return $this->modify(new ProfileRemovalModifier());
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::reduceColors()
     */
    public function reduceColors(int $limit, mixed $background = 'transparent'): ImageInterface
    {
        return $this->modify(new QuantizeColorsModifier($limit, $background));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::sharpen()
     */
    public function sharpen(int $amount = 10): ImageInterface
    {
        return $this->modify(new SharpenModifier($amount));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::invert()
     */
    public function invert(): ImageInterface
    {
        return $this->modify(new InvertModifier());
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::pixelate()
     */
    public function pixelate(int $size): ImageInterface
    {
        return $this->modify(new PixelateModifier($size));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::greyscale()
     */
    public function greyscale(): ImageInterface
    {
        return $this->modify(new GreyscaleModifier());
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::brightness()
     */
    public function brightness(int $level): ImageInterface
    {
        return $this->modify(new BrightnessModifier($level));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::contrast()
     */
    public function contrast(int $level): ImageInterface
    {
        return $this->modify(new ContrastModifier($level));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::gamma()
     */
    public function gamma(float $gamma): ImageInterface
    {
        return $this->modify(new GammaModifier($gamma));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::colorize()
     */
    public function colorize(int $red = 0, int $green = 0, int $blue = 0): ImageInterface
    {
        return $this->modify(new ColorizeModifier($red, $green, $blue));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::flip()
     */
    public function flip(): ImageInterface
    {
        return $this->modify(new FlipModifier());
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::flop()
     */
    public function flop(): ImageInterface
    {
        return $this->modify(new FlopModifier());
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::blur()
     */
    public function blur(int $amount = 5): ImageInterface
    {
        return $this->modify(new BlurModifier($amount));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::rotate()
     */
    public function rotate(float $angle, mixed $background = 'ffffff'): ImageInterface
    {
        return $this->modify(new RotateModifier($angle, $background));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::orient()
     */
    public function orient(): ImageInterface
    {
        return $this->modify(new AlignRotationModifier());
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::text()
     */
    public function text(string $text, int $x, int $y, callable|Closure|FontInterface $font): ImageInterface
    {
        return $this->modify(
            new TextModifier(
                $text,
                new Point($x, $y),
                call_user_func(new FontFactory($font)),
            ),
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::resize()
     */
    public function resize(?int $width = null, ?int $height = null): ImageInterface
    {
        return $this->modify(new ResizeModifier($width, $height));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::resizeDown()
     */
    public function resizeDown(?int $width = null, ?int $height = null): ImageInterface
    {
        return $this->modify(new ResizeDownModifier($width, $height));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::scale()
     */
    public function scale(?int $width = null, ?int $height = null): ImageInterface
    {
        return $this->modify(new ScaleModifier($width, $height));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::scaleDown()
     */
    public function scaleDown(?int $width = null, ?int $height = null): ImageInterface
    {
        return $this->modify(new ScaleDownModifier($width, $height));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::cover()
     */
    public function cover(int $width, int $height, string $position = 'center'): ImageInterface
    {
        return $this->modify(new CoverModifier($width, $height, $position));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::coverDown()
     */
    public function coverDown(int $width, int $height, string $position = 'center'): ImageInterface
    {
        return $this->modify(new CoverDownModifier($width, $height, $position));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::resizeCanvas()
     */
    public function resizeCanvas(
        ?int $width = null,
        ?int $height = null,
        mixed $background = 'ffffff',
        string $position = 'center'
    ): ImageInterface {
        return $this->modify(new ResizeCanvasModifier($width, $height, $background, $position));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::resizeCanvasRelative()
     */
    public function resizeCanvasRelative(
        ?int $width = null,
        ?int $height = null,
        mixed $background = 'ffffff',
        string $position = 'center'
    ): ImageInterface {
        return $this->modify(new ResizeCanvasRelativeModifier($width, $height, $background, $position));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::padDown()
     */
    public function pad(
        int $width,
        int $height,
        mixed $background = 'ffffff',
        string $position = 'center'
    ): ImageInterface {
        return $this->modify(new PadModifier($width, $height, $background, $position));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::pad()
     */
    public function contain(
        int $width,
        int $height,
        mixed $background = 'ffffff',
        string $position = 'center'
    ): ImageInterface {
        return $this->modify(new ContainModifier($width, $height, $background, $position));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::crop()
     */
    public function crop(
        int $width,
        int $height,
        int $offset_x = 0,
        int $offset_y = 0,
        mixed $background = 'ffffff',
        string $position = 'top-left'
    ): ImageInterface {
        return $this->modify(new CropModifier($width, $height, $offset_x, $offset_y, $background, $position));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::trim()
     */
    public function trim(int $tolerance = 0): ImageInterface
    {
        return $this->modify(new TrimModifier($tolerance));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::place()
     */
    public function place(
        mixed $element,
        string $position = 'top-left',
        int $offset_x = 0,
        int $offset_y = 0,
        int $opacity = 100
    ): ImageInterface {
        return $this->modify(new PlaceModifier($element, $position, $offset_x, $offset_y, $opacity));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::fill()
     */
    public function fill(mixed $color, ?int $x = null, ?int $y = null): ImageInterface
    {
        return $this->modify(
            new FillModifier(
                $color,
                is_null($x) || is_null($y) ? null : new Point($x, $y),
            ),
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::drawPixel()
     */
    public function drawPixel(int $x, int $y, mixed $color): ImageInterface
    {
        return $this->modify(new DrawPixelModifier(new Point($x, $y), $color));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::drawRectangle()
     */
    public function drawRectangle(int $x, int $y, callable|Closure|Rectangle $init): ImageInterface
    {
        return $this->modify(
            new DrawRectangleModifier(
                call_user_func(new RectangleFactory(new Point($x, $y), $init)),
            ),
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::drawEllipse()
     */
    public function drawEllipse(int $x, int $y, callable|Closure|Ellipse $init): ImageInterface
    {
        return $this->modify(
            new DrawEllipseModifier(
                call_user_func(new EllipseFactory(new Point($x, $y), $init)),
            ),
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::drawCircle()
     */
    public function drawCircle(int $x, int $y, callable|Closure|Circle $init): ImageInterface
    {
        return $this->modify(
            new DrawEllipseModifier(
                call_user_func(new CircleFactory(new Point($x, $y), $init)),
            ),
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::drawPolygon()
     */
    public function drawPolygon(callable|Closure|Polygon $init): ImageInterface
    {
        return $this->modify(
            new DrawPolygonModifier(
                call_user_func(new PolygonFactory($init)),
            ),
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::drawLine()
     */
    public function drawLine(callable|Closure|Line $init): ImageInterface
    {
        return $this->modify(
            new DrawLineModifier(
                call_user_func(new LineFactory($init)),
            ),
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::drawBezier()
     */
    public function drawBezier(callable|Closure|Bezier $init): ImageInterface
    {
        return $this->modify(
            new DrawBezierModifier(
                call_user_func(new BezierFactory($init)),
            ),
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::encodeByMediaType()
     */
    public function encodeByMediaType(null|string|MediaType $type = null, mixed ...$options): EncodedImageInterface
    {
        return $this->encode(new MediaTypeEncoder($type, ...$options));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::encodeByExtension()
     */
    public function encodeByExtension(
        null|string|FileExtension $extension = null,
        mixed ...$options
    ): EncodedImageInterface {
        return $this->encode(new FileExtensionEncoder($extension, ...$options));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::encodeByPath()
     */
    public function encodeByPath(?string $path = null, mixed ...$options): EncodedImageInterface
    {
        return $this->encode(new FilePathEncoder($path, ...$options));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::toJpeg()
     */
    public function toJpeg(mixed ...$options): EncodedImageInterface
    {
        return $this->encode(new JpegEncoder(...$options));
    }

    /**
     * Alias of self::toJpeg()
     *
     * @throws RuntimeException
     */
    public function toJpg(mixed ...$options): EncodedImageInterface
    {
        return $this->toJpeg(...$options);
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::toJpeg()
     */
    public function toJpeg2000(mixed ...$options): EncodedImageInterface
    {
        return $this->encode(new Jpeg2000Encoder(...$options));
    }

    /**
     * ALias of self::toJpeg2000()
     *
     * @throws RuntimeException
     */
    public function toJp2(mixed ...$options): EncodedImageInterface
    {
        return $this->toJpeg2000(...$options);
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::toPng()
     */
    public function toPng(mixed ...$options): EncodedImageInterface
    {
        return $this->encode(new PngEncoder(...$options));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::toGif()
     */
    public function toGif(mixed ...$options): EncodedImageInterface
    {
        return $this->encode(new GifEncoder(...$options));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::toWebp()
     */
    public function toWebp(mixed ...$options): EncodedImageInterface
    {
        return $this->encode(new WebpEncoder(...$options));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::toBitmap()
     */
    public function toBitmap(mixed ...$options): EncodedImageInterface
    {
        return $this->encode(new BmpEncoder(...$options));
    }

    /**
     * Alias if self::toBitmap()
     *
     * @throws RuntimeException
     */
    public function toBmp(mixed ...$options): EncodedImageInterface
    {
        return $this->toBitmap(...$options);
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::toAvif()
     */
    public function toAvif(mixed ...$options): EncodedImageInterface
    {
        return $this->encode(new AvifEncoder(...$options));
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::toTiff()
     */
    public function toTiff(mixed ...$options): EncodedImageInterface
    {
        return $this->encode(new TiffEncoder(...$options));
    }

    /**
     * Alias of self::toTiff()
     *
     * @throws RuntimeException
     */
    public function toTif(mixed ...$options): EncodedImageInterface
    {
        return $this->toTiff(...$options);
    }

    /**
     * {@inheritdoc}
     *
     * @see ImageInterface::toHeic()
     */
    public function toHeic(mixed ...$options): EncodedImageInterface
    {
        return $this->encode(new HeicEncoder(...$options));
    }

    /**
     * Show debug info for the current image
     *
     * @return array<string, int>
     */
    public function __debugInfo(): array
    {
        try {
            return [
                'width' => $this->width(),
                'height' => $this->height(),
            ];
        } catch (RuntimeException) {
            return [];
        }
    }

    /**
     * Clone image
     */
    public function __clone(): void
    {
        $this->driver = clone $this->driver;
        $this->core = clone $this->core;
        $this->exif = clone $this->exif;
    }
}
