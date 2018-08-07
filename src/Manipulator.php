<?php

namespace LireinCore\Image;

interface Manipulator
{
    const DRIVER_DEFAULT = 1; //default graphic driver
    const DRIVER_GM = 2; //gmagick graphic driver
    const DRIVER_IM = 3; //imagick graphic driver
    const DRIVER_GD = 4; //gd graphic driver

    const MIN_REQUIRED_GM_VER = '1.0.0';
    const MIN_REQUIRED_IM_VER = '6.2.9';
    const MIN_REQUIRED_GD_VER = '2.0.1';

    const FILTER_UNDEFINED = 'undefined';
    const FILTER_POINT = 'point';
    const FILTER_BOX = 'box';
    const FILTER_TRIANGLE = 'triangle';
    const FILTER_HERMITE = 'hermite';
    const FILTER_HANNING = 'hanning';
    const FILTER_HAMMING = 'hamming';
    const FILTER_BLACKMAN = 'blackman';
    const FILTER_GAUSSIAN = 'gaussian';
    const FILTER_QUADRATIC = 'quadratic';
    const FILTER_CUBIC = 'cubic';
    const FILTER_CATROM = 'catrom';
    const FILTER_MITCHELL = 'mitchell';
    const FILTER_LANCZOS = 'lanczos';
    const FILTER_BESSEL = 'bessel';
    const FILTER_SINC = 'sinc';

    /**
     * @param int $driver
     * @param bool $tryToUseOtherDrivers
     * @throws \RuntimeException
     */
    public function __construct($driver = self::DRIVER_DEFAULT, $tryToUseOtherDrivers = true);

    /**
     * @return Manipulator
     * @throws \RuntimeException
     */
    public function instance();

    /**
     * @param $filepath
     * @return $this
     * @throws \RuntimeException
     */
    public function open($filepath);

    /**
     * @param int $width
     * @param int $height
     * @param string $color
     * @param int $transparency
     * @return $this
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function create($width, $height, $color = '#fff', $transparency = 0);

    /**
     * @param string $destPath
     * @param array $options
     * @return $this
     * @throws \RuntimeException
     */
    public function save($destPath, $options = []);

    /**
     * @return Manipulator
     * @throws \RuntimeException
     */
    public function copy();

    /**
     * @param Effect $effect
     * @return $this
     * @throws \InvalidArgumentException
     * @throws \OutOfBoundsException
     * @throws \RuntimeException
     */
    public function apply(Effect $effect);

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
    public function paste(Manipulator $manipulator, $offsetX, $offsetY, $opacity = 100);

    /**
     * @param int $width
     * @param int $height
     * @param string $filter
     * @return $this
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function resize($width, $height, $filter = self::FILTER_UNDEFINED);

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
    public function crop($offsetX, $offsetY, $width, $height);

    /**
     * @param float $ratio
     * @param string $filter
     * @return $this
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function scale($ratio, $filter = self::FILTER_UNDEFINED);

    /**
     * @return $this
     * @throws \RuntimeException
     */
    public function flipHorizontally();

    /**
     * @return $this
     * @throws \RuntimeException
     */
    public function flipVertically();

    /**
     * @param float $angle
     * @param string $bgcolor
     * @param int $bgtransparency
     * @return $this
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function rotate($angle, $bgcolor = '#fff', $bgtransparency = 0);

    /**
     * @return $this
     * @throws \RuntimeException
     */
    public function negative();

    /**
     * @return $this
     * @throws \RuntimeException
     */
    public function grayscale();

    /**
     * @param float $correction
     * @return $this
     * @throws \RuntimeException
     */
    public function gamma($correction);

    /**
     * @param float $sigma
     * @return $this
     * @throws \RuntimeException
     */
    public function blur($sigma);

    /**
     * @param string $text
     * @param string $font
     * @param int $offsetX
     * @param int $offsetY
     * @param int $size
     * @param string|array $color
     * @param int $opacity
     * @param float|int $angle
     * @param null|int $width
     * @return $this
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function text($text, $font = 'Times New Roman', $offsetX = 0, $offsetY = 0, $size = 12, $color = '#fff', $opacity = 100, $angle = 0, $width = null);

    /**
     * @return int
     */
    public function driver();

    /**
     * @return int
     */
    public function width();

    /**
     * @return int
     */
    public function height();
}