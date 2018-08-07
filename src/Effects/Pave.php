<?php

namespace LireinCore\Image\Effects;

use LireinCore\Image\Pixel;
use LireinCore\Image\Effect;
use LireinCore\Image\Manipulator;

/**
 * Pave image //todo: not implemented
 */
class Pave implements Effect
{
    use Pixel;

    const MODE_REPEAT_X = 0b00000001;
    const MODE_REPEAT_Y = 0b00000010;
    const MODE_SPACE_X = 0b00000100;
    const MODE_SPACE_Y = 0b00001000;
    const MODE_ROUND_X = 0b00010000;
    const MODE_ROUND_Y = 0b00100000;

    /**
     * @var string
     */
    protected $offsetX;

    /**
     * @var string
     */
    protected $offsetY;

    /**
     * @var string
     */
    protected $width;

    /**
     * @var string
     */
    protected $height;

    /**
     * @var int
     */
    protected $mode;

    /**
     * Pave constructor.
     *
     * @param string $offset_x for example: 100 | 20% | center | left | right (default: left)
     * @param string $offset_y for example: 100 | 20% | center | top | bottom (default: top)
     * @param string $width for example: 100 | 20% (default: auto)
     * @param string $height for example: 100 | 20% (default: auto)
     * @param int    $mode
     */
    public function __construct($offset_x, $offset_y, $width, $height, $mode = 0b00000001)
    {
        $this->offsetX = $offset_x;
        $this->offsetY = $offset_y;
        $this->width = $width;
        $this->height = $height;
        $this->mode = $mode;
    }

    /**
     * @inheritdoc
     */
    public function apply(Manipulator $manipulator)
    {
        $origWidth = $manipulator->width();
        $origHeight = $manipulator->height();
        $width = $this->pxSize($this->width, $origWidth);
        $height = $this->pxSize($this->height, $origHeight);
        $offsetX = $this->pxOffset($this->offsetX, $manipulator->width(), $width);
        $offsetY = $this->pxOffset($this->offsetY, $manipulator->height(), $height);

        //todo

        return $this;
    }
}