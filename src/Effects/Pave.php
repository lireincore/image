<?php

namespace LireinCore\Image\Effects;

use LireinCore\Image\EffectInterface;
use LireinCore\Image\TPixel;
use LireinCore\Image\ImageInterface;

/**
 * Pave image //todo: not implemented
 */
class Pave implements EffectInterface
{
    use TPixel;

    const MODE_REPEAT_X = 0b00000001;
    const MODE_REPEAT_Y = 0b00000010;
    const MODE_SPACE_X = 0b00000100;
    const MODE_SPACE_Y = 0b00001000;
    const MODE_ROUND_X = 0b00010000;
    const MODE_ROUND_Y = 0b00100000;

    /**
     * @var string
     */
    protected $_offsetX;

    /**
     * @var string
     */
    protected $_offsetY;

    /**
     * @var string
     */
    protected $_width;

    /**
     * @var string
     */
    protected $_height;

    /**
     * @var int
     */
    protected $_mode;

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
        $this->_offsetX = $offset_x;
        $this->_offsetY = $offset_y;
        $this->_width = $width;
        $this->_height = $height;
        $this->_mode = $mode;
    }

    /**
     * @inheritdoc
     */
    public function apply(ImageInterface $img)
    {
        $origWidth = $img->getWidth();
        $origHeight = $img->getHeight();
        $width = $this->getPxSize($this->_width, $origWidth);
        $height = $this->getPxSize($this->_height, $origHeight);
        $offsetX = $this->getPxOffset($this->_offsetX, $img->getWidth(), $width);
        $offsetY = $this->getPxOffset($this->_offsetY, $img->getHeight(), $height);

        //todo

        return $this;
    }
}