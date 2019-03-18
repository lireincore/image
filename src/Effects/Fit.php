<?php

namespace LireinCore\Image\Effects;

use LireinCore\Image\Pixel;
use LireinCore\Image\Effect;
use LireinCore\Image\Manipulator;

/**
 * Fit image in box
 */
final class Fit implements Effect
{
    use Pixel;

    /**
     * @var string
     */
    private $offsetX;

    /**
     * @var string
     */
    private $offsetY;

    /**
     * @var string|null
     */
    private $width;

    /**
     * @var string|null
     */
    private $height;

    /**
     * @var string
     */
    private $bgColor;

    /**
     * @var int
     */
    private $bgTransparency;

    /**
     * @var bool
     */
    private $allowIncrease;

    /**
     * Fit constructor.
     *
     * @param string      $offset_x for example: 100 | 20% | center | left | right (default: center)
     * @param string      $offset_y for example: 100 | 20% | center | top | bottom (default: center)
     * @param string|null $width for example: 100 | 20% (default: auto)
     * @param string|null $height for example: 100 | 20% (default: auto)
     * @param string      $bgcolor for example: '#fff' or '#ffffff' - hex | '50,50,50' - rgb | '50,50,50,50' - cmyk (default: #fff)
     * @param int         $bgtransparency for example: 0-100, 0 - not transparent | 100 - fully transparent (default: 0)
     * @param bool        $allow_increase increase if image is less (default: false)
     */
    public function __construct(
        $offset_x = 'center',
        $offset_y = 'center',
        $width = null,
        $height = null,
        $bgcolor = '#fff',
        $bgtransparency = 0,
        $allow_increase = false
    )
    {
        $this->offsetX = $offset_x;
        $this->offsetY = $offset_y;
        $this->width = $width;
        $this->height = $height;
        $this->bgColor = $bgcolor;
        $this->bgTransparency = $bgtransparency;
        $this->allowIncrease = $allow_increase;
    }

    /**
     * @inheritdoc
     */
    public function apply(Manipulator $manipulator) : Effect
    {
        if ($this->width !== null || $this->height !== null) {
            $origWidth = $manipulator->width();
            $origHeight = $manipulator->height();
            $origAspectRatio = $origHeight / $origWidth;

            if ($this->width === null) {
                $height = $this->pxSize($this->height, $origHeight);
                $width = (int)round($height / $origAspectRatio);
            } elseif ($this->height === null) {
                $width = $this->pxSize($this->width, $origWidth);
                $height = (int)round($width * $origAspectRatio);
            } else {
                $width = $this->pxSize($this->width, $origWidth);
                $height = $this->pxSize($this->height, $origHeight);
            }

            if ($origWidth !== $width || $origHeight !== $height) {
                $manipulator->apply(new ScaleUp($width, $height, $this->allowIncrease));
                $newWidth = $manipulator->width();
                $newHeight = $manipulator->height();
                if ($newWidth !== $width || $newHeight !== $height) {
                    $manipulatorCopy = $manipulator->copy();
                    $bgColor = $this->parseColor($this->bgColor);
                    $manipulator->create($width, $height, $bgColor, $this->bgTransparency);
                    $offsetX = $this->wtOffset($this->offsetX, $width, $manipulatorCopy->width());
                    $offsetY = $this->wtOffset($this->offsetY, $height, $manipulatorCopy->height());
                    $manipulator->paste($manipulatorCopy, $offsetX, $offsetY);
                }
            }
        }

        return $this;
    }
}