<?php

namespace LireinCore\Image;

interface Manipulator
{
    public const DRIVER_DEFAULT = 1; //default graphic driver
    public const DRIVER_GM = 2;      //gmagick graphic driver
    public const DRIVER_IM = 3;      //imagick graphic driver
    public const DRIVER_GD = 4;      //gd graphic driver

    public const MIN_REQUIRED_GM_VER = '1.0.0';
    public const MIN_REQUIRED_IM_VER = '6.2.9';
    public const MIN_REQUIRED_GD_VER = '2.0.35';

    public const FILTER_UNDEFINED = 'undefined';
    public const FILTER_POINT = 'point';
    public const FILTER_BOX = 'box';
    public const FILTER_TRIANGLE = 'triangle';
    public const FILTER_HERMITE = 'hermite';
    public const FILTER_HANNING = 'hanning';
    public const FILTER_HAMMING = 'hamming';
    public const FILTER_BLACKMAN = 'blackman';
    public const FILTER_GAUSSIAN = 'gaussian';
    public const FILTER_QUADRATIC = 'quadratic';
    public const FILTER_CUBIC = 'cubic';
    public const FILTER_CATROM = 'catrom';
    public const FILTER_MITCHELL = 'mitchell';
    public const FILTER_LANCZOS = 'lanczos';
    public const FILTER_BESSEL = 'bessel';
    public const FILTER_SINC = 'sinc';

    /**
     * @param int $driver
     * @param bool $tryToUseOtherDrivers
     * @throws \RuntimeException
     */
    public function __construct(int $driver = self::DRIVER_DEFAULT, bool $tryToUseOtherDrivers = true);

    /**
     * @return Manipulator
     * @throws \RuntimeException
     */
    public function instance() : Manipulator;

    /**
     * @param string $filepath
     * @return $this
     * @throws \RuntimeException
     */
    public function open(string $filepath) : self;

    /**
     * @param int $width
     * @param int $height
     * @param string|array $color
     * @param int $transparency
     * @return $this
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function create(int $width, int $height, $color = '#fff', int $transparency = 0) : self;

    /**
     * @param string $destPath
     * @param array $options
     * @return $this
     * @throws \RuntimeException
     */
    public function save(string $destPath, array $options = []) : self;

    /**
     * @return Manipulator
     * @throws \RuntimeException
     */
    public function copy() : Manipulator;

    /**
     * @param Effect $effect
     * @return $this
     * @throws \InvalidArgumentException
     * @throws \OutOfBoundsException
     * @throws \RuntimeException
     */
    public function apply(Effect $effect) : self;

    /**
     * @param Manipulator $manipulator
     * @param int $offsetX
     * @param int $offsetY
     * @param int $opacity
     * @return $this
     * @throws \InvalidArgumentException
     * @throws \OutOfBoundsException
     * @throws \RuntimeException
     */
    public function paste(Manipulator $manipulator, int $offsetX, int $offsetY, int $opacity = 100) : self;

    /**
     * @param int $width
     * @param int $height
     * @param string $filter
     * @return $this
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function resize(int $width, int $height, string $filter = self::FILTER_UNDEFINED) : self;

    /**
     * @param int $offsetX
     * @param int $offsetY
     * @param int $width
     * @param int $height
     * @return $this
     * @throws \InvalidArgumentException
     * @throws \OutOfBoundsException
     * @throws \RuntimeException
     */
    public function crop(int $offsetX, int $offsetY, int $width, int $height) : self;

    /**
     * @param float $ratio
     * @param string $filter
     * @return $this
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function scale(float $ratio, string $filter = self::FILTER_UNDEFINED) : self;

    /**
     * @return $this
     * @throws \RuntimeException
     */
    public function flipHorizontally() : self;

    /**
     * @return $this
     * @throws \RuntimeException
     */
    public function flipVertically() : self;

    /**
     * @param int $angle
     * @param string $bgcolor
     * @param int $bgtransparency
     * @return $this
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function rotate(int $angle, string $bgcolor = '#fff', int $bgtransparency = 0) : self;

    /**
     * @return $this
     * @throws \RuntimeException
     */
    public function negative() : self;

    /**
     * @return $this
     * @throws \RuntimeException
     */
    public function grayscale() : self;

    /**
     * @param float $correction
     * @return $this
     * @throws \RuntimeException
     */
    public function gamma(float $correction) : self;

    /**
     * @param float $sigma
     * @return $this
     * @throws \RuntimeException
     */
    public function blur(float $sigma) : self;

    /**
     * @param string $text
     * @param string $font
     * @param int $offsetX
     * @param int $offsetY
     * @param int $size
     * @param string|array $color
     * @param int $opacity
     * @param int $angle
     * @param int|null $width
     * @return $this
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function text(
        string $text,
        string $font = 'Times New Roman',
        int $offsetX = 0,
        int $offsetY = 0,
        int $size = 12,
        $color = '#fff',
        int $opacity = 100,
        int $angle = 0,
        ?int $width = null
    ) : self;

    /**
     * @return int
     */
    public function driver() : int;

    /**
     * @return int
     */
    public function width() : int;

    /**
     * @return int
     */
    public function height() : int;
}