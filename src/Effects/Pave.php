<?php

namespace LireinCore\Image\Effects;

use LireinCore\Image\Pixel;
use LireinCore\Image\Effect;
use LireinCore\Image\Manipulator;

/**
 * Pave image
 */
final class Pave implements Effect
{
    use Pixel;

    public const MODE_REPEAT_X = 0b00000001;
    public const MODE_REPEAT_Y = 0b00000010;
    public const MODE_SPACE_X = 0b00000100;
    public const MODE_SPACE_Y = 0b00001000;
    public const MODE_ROUND_X = 0b00010000;
    public const MODE_ROUND_Y = 0b00100000;

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
     * @var int
     */
    private $mode;

    /**
     * Pave constructor.
     *
     * @param string      $offset_x for example: 100 | 20% | center | left | right (default: left)
     * @param string      $offset_y for example: 100 | 20% | center | top | bottom (default: top)
     * @param string|null $width for example: 100 | 20% (default: auto)
     * @param string|null $height for example: 100 | 20% (default: auto)
     * @param int         $mode
     */
    public function __construct($offset_x = 'left', $offset_y = 'top', $width = null, $height = null, $mode = 0b00000001)
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
    public function apply(Manipulator $manipulator) : Effect
    {
        $origWidth = $manipulator->width();
        $origHeight = $manipulator->height();
        $width = $this->pxSize($this->width, $origWidth);
        $height = $this->pxSize($this->height, $origHeight);
        $offsetX = $this->pxOffset($this->offsetX, $manipulator->width(), $width);
        $offsetY = $this->pxOffset($this->offsetY, $manipulator->height(), $height);

        //todo: not implemented

        return $this;
    }
}