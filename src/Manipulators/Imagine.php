<?php

namespace LireinCore\Image\Manipulators;

use LireinCore\Image\Effect;
use LireinCore\Image\Manipulator;
use LireinCore\Image\ImageHelper;
use Imagine\Image\Box;
use Imagine\Image\Point;
use Imagine\Image\Palette\RGB;
use Imagine\Image\AbstractImage;
use Imagine\Image\AbstractImagine;

final class Imagine implements Manipulator
{
    /**
     * @var AbstractImagine
     */
    private $imagine;
    
    /**
     * @var AbstractImage
     */
    private $image;
    
    /**
     * @var string
     */
    private $driver;

    /**
     * Image constructor.
     *
     * @param int $driver
     * @param bool $tryToUseOtherDrivers
     * @throws \RuntimeException
     */
    public function __construct(int $driver = Manipulator::DRIVER_DEFAULT, bool $tryToUseOtherDrivers = true)
    {
        if ($driver === Manipulator::DRIVER_IM || $driver === Manipulator::DRIVER_DEFAULT) {
            try {
                $this->imagine = new \Imagine\Imagick\Imagine();
                $this->driver = Manipulator::DRIVER_IM;
            } catch (\RuntimeException $ex1) {
                if ($tryToUseOtherDrivers) {
                    try {
                        $this->imagine = new \Imagine\Gd\Imagine();
                        $this->driver = Manipulator::DRIVER_GD;
                    } catch (\RuntimeException $ex2) {
                        try {
                            $this->imagine = new \Imagine\Gmagick\Imagine();
                            $this->driver = Manipulator::DRIVER_GM;
                        } catch (\RuntimeException $ex3) {
                            throw new \RuntimeException('Graphic library not installed or higher version is required');
                        }
                    }
                } else {
                    throw new \RuntimeException('Imagick not installed or higher version is required', 0, $ex1);
                }
            }
        } elseif ($driver === Manipulator::DRIVER_GM) {
            try {
                $this->imagine = new \Imagine\Gmagick\Imagine();
                $this->driver = Manipulator::DRIVER_GM;
            } catch (\RuntimeException $ex1) {
                if ($tryToUseOtherDrivers) {
                    try {
                        $this->imagine = new \Imagine\Imagick\Imagine();
                        $this->driver = Manipulator::DRIVER_IM;
                    } catch (\RuntimeException $ex2) {
                        try {
                            $this->imagine = new \Imagine\Gd\Imagine();
                            $this->driver = Manipulator::DRIVER_GD;
                        } catch (\RuntimeException $ex3) {
                            throw new \RuntimeException('Graphic library not installed or higher version is required');
                        }
                    }
                } else {
                    throw new \RuntimeException('Gmagick not installed or higher version is required', 0, $ex1);
                }
            }
        } elseif ($driver === Manipulator::DRIVER_GD) {
            try {
                $this->imagine = new \Imagine\Gd\Imagine();
                $this->driver = Manipulator::DRIVER_GD;
            } catch (\RuntimeException $ex1) {
                if ($tryToUseOtherDrivers) {
                    try {
                        $this->imagine = new \Imagine\Imagick\Imagine();
                        $this->driver = Manipulator::DRIVER_IM;
                    } catch (\RuntimeException $ex2) {
                        try {
                            $this->imagine = new \Imagine\Gmagick\Imagine();
                            $this->driver = Manipulator::DRIVER_GM;
                        } catch (\RuntimeException $ex3) {
                            throw new \RuntimeException('Graphic library not installed or higher version is required');
                        }
                    }
                } else {
                    throw new \RuntimeException('Gd not installed or higher version is required', 0, $ex1);
                }
            }
        } else {
            throw new \RuntimeException('Unknown graphic library');
        }
    }

    /**
     * @return Manipulator
     * @throws \RuntimeException
     */
    public function instance() : Manipulator
    {
        return new static($this->driver(), false);
    }

    /**
     * @param string $filepath
     * @return $this
     * @throws \RuntimeException
     */
    public function open(string $filepath) : Manipulator
    {
        $this->image = $this->imagine()->open($filepath);

        return $this;
    }

    /**
     * @param int $width
     * @param int $height
     * @param string|array $color
     * @param int $transparency
     * @return $this
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function create(int $width, int $height, $color = '#fff', int $transparency = 0) : Manipulator
    {
        $size = new Box($width, $height);
        $palette = new RGB();
        if ($this->driver() === Manipulator::DRIVER_GM) {
            // @todo: transparency not supported
            $colorObj = $palette->color($color);
        } else {
            $colorObj = $palette->color($color, $transparency);
        }
        $this->image = $this->imagine()->create($size, $colorObj);

        return $this;
    }

    /**
     * @param string $destPath
     * @param array $options
     * @return $this
     * @throws \RuntimeException
     */
    public function save(string $destPath, array $options = []) : Manipulator
    {
        $destPathInfo = pathinfo($destPath);
        if (!is_dir($destPathInfo['dirname'])) {
            ImageHelper::rmkdir($destPathInfo['dirname']);
        }

        // @todo: png_compression_filter with GD have another format
        if ($this->driver() === Manipulator::DRIVER_GD) {
            unset($options['png_compression_filter']);
        }

        $this->image()->save($destPath, $options);

        return $this;
    }

    /**
     * @return Manipulator
     * @throws \RuntimeException
     */
    public function copy() : Manipulator
    {
        $manipulator = new static($this->driver(), false);
        $manipulator->image = $this->image()->copy();

        return $manipulator;
    }

    /**
     * @param Effect $effect
     * @return $this
     * @throws \InvalidArgumentException
     * @throws \OutOfBoundsException
     * @throws \RuntimeException
     */
    public function apply(Effect $effect) : Manipulator
    {
        $effect->apply($this);

        return $this;
    }

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
    public function paste(Manipulator $manipulator, int $offsetX, int $offsetY, int $opacity = 100) : Manipulator
    {
        if (!$manipulator instanceof static) {
            throw new \InvalidArgumentException(sprintf('LireinCore\Image\Manipulators\Imagine can only paste() LireinCore\Image\Manipulators\Imagine instances, %s given', get_class($manipulator)));
        }

        if ($opacity !== 100) {
            $overlayRes = $manipulator->driverResObject();
            if ($this->driver() === Manipulator::DRIVER_GM) {
                // @todo: opacity not supported
            } elseif ($this->driver() === Manipulator::DRIVER_IM) {
                if (ImageHelper::isImagickAvailable('6.3.1')) {
                    // setImageOpacity was replaced with setImageAlpha in php-imagick v3.4.3
                    if (method_exists($overlayRes, 'setImageAlpha')) {
                        $overlayRes->setImageAlpha($opacity / 100);
                    } else {
                        $overlayRes->setImageOpacity($opacity / 100);
                    }
                }
            } elseif ($this->driver() === Manipulator::DRIVER_GD) {
                $width = $manipulator->width();
                $height = $manipulator->height();
                $originRes = $this->driverResObject();

                $cutRes = imagecreatetruecolor($width, $height);
                imagecopy($cutRes, $originRes, 0, 0, $offsetX, $offsetY, $width, $height);
                imagecopy($cutRes, $overlayRes, 0, 0, 0, 0, $width, $height);
                imagecopymerge($originRes, $cutRes, $offsetX, $offsetY, 0, 0, $width, $height, $opacity);
                imagedestroy($cutRes);
            }
        }

        if ($opacity === 100 || $this->driver() === Manipulator::DRIVER_GM || $this->driver() === Manipulator::DRIVER_IM) {
            $this->image()->paste($manipulator->image(), new Point($offsetX, $offsetY));
        }

        return $this;
    }

    /**
     * @param int $width
     * @param int $height
     * @param string $filter
     * @return $this
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function resize(int $width, int $height, string $filter = Manipulator::FILTER_UNDEFINED) : Manipulator
    {
        if ($width > 0 && $height > 0) {
            $origWidth = $this->width();
            $origHeight = $this->height();
            if ($origWidth !== $width || $origHeight !== $height) {
                $this->image()->resize(new Box($width, $height), $filter);
            }
        }

        return $this;
    }

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
    public function crop(int $offsetX, int $offsetY, int $width, int $height) : Manipulator
    {
        if ($width > 0 && $height > 0 && $offsetX >= 0 && $offsetY >= 0) {
            $origWidth = $this->width();
            $origHeight = $this->height();
            if ($offsetX + $width < $origWidth && $offsetY + $height < $origHeight) {
                if ($offsetX !== 0 || $offsetY !== 0 || $origWidth !== $width || $origHeight !== $height) {
                    $this->image()->crop(new Point($offsetX, $offsetY), new Box($width, $height));
                }
            }
        }

        return $this;
    }

    /**
     * @param float $ratio
     * @param string $filter
     * @return $this
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function scale(float $ratio, string $filter = Manipulator::FILTER_UNDEFINED) : Manipulator
    {
        $ratio = abs($ratio);
        $newWidth = round($ratio * $this->width());
        $newHeight = round($ratio * $this->height());

        return $this->resize($newWidth, $newHeight, $filter);
    }

    /**
     * @return $this
     * @throws \RuntimeException
     */
    public function flipHorizontally() : Manipulator
    {
        $this->image()->flipHorizontally();

        return $this;
    }

    /**
     * @return $this
     * @throws \RuntimeException
     */
    public function flipVertically() : Manipulator
    {
        $this->image()->flipVertically();

        return $this;
    }

    /**
     * @param int $angle
     * @param string|array $bgcolor
     * @param int $bgtransparency
     * @return $this
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function rotate(int $angle, $bgcolor = '#fff', int $bgtransparency = 0) : Manipulator
    {
        $palette = new RGB();
        if ($this->driver() === Manipulator::DRIVER_GM) {
            // @todo: transparency not supported
            $colorObj = $palette->color($bgcolor);
        } else {
            $colorObj = $palette->color($bgcolor, $bgtransparency);
        }
        $this->image()->rotate($angle, $colorObj);

        return $this;
    }

    /**
     * @return $this
     * @throws \RuntimeException
     */
    public function negative() : Manipulator
    {
        $this->image()->effects()->negative();

        return $this;
    }

    /**
     * @return $this
     * @throws \RuntimeException
     */
    public function grayscale() : Manipulator
    {
        $this->image()->effects()->grayscale();

        return $this;
    }

    /**
     * @param float $correction
     * @return $this
     * @throws \RuntimeException
     */
    public function gamma(float $correction) : Manipulator
    {
        $this->image()->effects()->gamma($correction);

        return $this;
    }

    /**
     * @param float $sigma
     * @return $this
     * @throws \RuntimeException
     */
    public function blur(float $sigma) : Manipulator
    {
        $this->image()->effects()->blur($sigma);

        return $this;
    }

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
    ) : Manipulator
    {
        $palette = new RGB();
        if ($this->driver() === Manipulator::DRIVER_GM) {
            // @todo: transparency not supported
            $colorObj = $palette->color($color);
        } else {
            $colorObj = $palette->color($color, $opacity);
        }
        $fontObj = $this->imagine()->font($font, $size, $colorObj);
        $this->image()->draw()->text($text, $fontObj, new Point($offsetX, $offsetY), $angle, $width);

        return $this;
    }

    /**
     * @return int
     */
    public function driver() : int
    {
        return $this->driver;
    }

    /**
     * @return int
     */
    public function width() : int
    {
        $size = $this->image()->getSize();

        return $size->getWidth();
    }

    /**
     * @return int
     */
    public function height() : int
    {
        $size = $this->image()->getSize();

        return $size->getHeight();
    }

    /**
     * @return AbstractImagine
     */
    private function imagine() : AbstractImagine
    {
        return $this->imagine;
    }

    /**
     * @return AbstractImage
     */
    private function image() : AbstractImage
    {
        return $this->image;
    }

    /**
     * @return \Imagick|\Gmagick|resource
     */
    private function driverResObject()
    {
        if ($this->driver() === static::DRIVER_GM) {
            return $this->image()->getGmagick();
        }

        if ($this->driver() === static::DRIVER_IM) {
            return $this->image()->getImagick();
        }

        return $this->image()->getGdResource();
    }
}