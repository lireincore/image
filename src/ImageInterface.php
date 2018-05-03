<?php

namespace LireinCore\Image;

interface ImageInterface
{
    const DRIVER_DEFAULT = 1; //default graphic driver
    const DRIVER_GM = 2; //gmagick graphic driver
    const DRIVER_IM = 3; //imagick graphic driver
    const DRIVER_GD = 4; //gd2 graphic driver

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
    public function __construct($driver = ImageInterface::DRIVER_DEFAULT, $tryToUseOtherDrivers = true);

    /**
     * @param int $driver
     * @param bool $tryToUseOtherDrivers
     * @return ImageInterface
     * @throws \RuntimeException
     */
    public static function newInstance($driver = ImageInterface::DRIVER_DEFAULT, $tryToUseOtherDrivers = true);

    /**
     * @return int
     */
    public function getDriver();

    /**
     * @return int
     */
    public function getWidth();

    /**
     * @return int
     */
    public function getHeight();

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
     */
    public function create($width, $height, $color = '#fff', $transparency = 0);

    /**
     * @return ImageInterface
     */
    public function copy();

    /**
     * @param EffectInterface $effect
     * @return $this
     */
    public function apply(EffectInterface $effect);

    /**
     * @param ImageInterface $img
     * @param int $offsetX
     * @param int $offsetY
     * @param int $opacity
     * @return $this
     */
    public function paste(ImageInterface $img, $offsetX, $offsetY, $opacity = 100);

    /**
     * @param int $width
     * @param int $height
     * @param string $filter
     * @return $this
     */
    public function resize($width, $height, $filter = ImageInterface::FILTER_UNDEFINED);

    /**
     * @param int $offsetX
     * @param int $offsetY
     * @param int $width
     * @param int $height
     * @return $this
     */
    public function crop($offsetX, $offsetY, $width, $height);

    /**
     * @param float $ratio
     * @param string $filter
     * @return $this
     */
    public function scale($ratio, $filter = ImageInterface::FILTER_UNDEFINED);

    /**
     * @return $this
     */
    public function flipHorizontally();

    /**
     * @return $this
     */
    public function flipVertically();

    /**
     * @param float $angle
     * @param string $bgcolor
     * @param int $bgtransparency
     * @return $this
     */
    public function rotate($angle, $bgcolor = '#fff', $bgtransparency = 0);

    /**
     * @return $this
     */
    public function negative();

    /**
     * @return $this
     */
    public function grayscale();

    /**
     * @param float $correction
     * @return $this
     */
    public function gamma($correction);

    /**
     * @param float $sigma
     * @return $this
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
     */
    public function text($text, $font = 'Times New Roman', $offsetX = 0, $offsetY = 0, $size = 12, $color = '#fff', $opacity = 100, $angle = 0, $width = null);

    /**
     * @param string $destPath
     * @param array $options
     * @return $this
     * @throws \RuntimeException
     */
    public function save($destPath, $options = []);
}
