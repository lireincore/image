<?php

namespace LireinCore\Image\Effects;

use LireinCore\Image\EffectInterface;
use LireinCore\Image\TPixel;
use LireinCore\Image\ImageInterface;

/**
 * Crop image
 */
class Crop implements EffectInterface
{
    use TPixel;

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
     * Crop constructor.
     *
     * @param string $offset_x for example: 100 | 20% | center | left | right (default: left)
     * @param string $offset_y for example: 100 | 20% | center | top | bottom (default: top)
     * @param string $width for example: 100 | 20% (default: auto)
     * @param string $height for example: 100 | 20% (default: auto)
     */
    public function __construct($offset_x = 'left', $offset_y = 'top', $width = null, $height = null)
    {
        $this->_offsetX = $offset_x;
        $this->_offsetY = $offset_y;
        $this->_width = $width;
        $this->_height = $height;
    }

    /**
     * @inheritdoc
     */
    public function apply(ImageInterface $img)
    {
        if ($this->_width !== null || $this->_height !== null) {
            $origWidth = $img->getWidth();
            $origHeight = $img->getHeight();
            $origAspectRatio = $origHeight / $origWidth;

            if ($this->_width === null) {
                $height = $this->getPxSize($this->_height, $origHeight);
                $width = (int)round($height / $origAspectRatio);
            } elseif ($this->_height === null) {
                $width = $this->getPxSize($this->_width, $origWidth);
                $height = (int)round($width * $origAspectRatio);
            } else {
                $width = $this->getPxSize($this->_width, $origWidth);
                $height = $this->getPxSize($this->_height, $origHeight);
            }

            $offsetX = $this->getPxOffset($this->_offsetX, $origWidth, $width);
            $offsetY = $this->getPxOffset($this->_offsetY, $origHeight, $height);

            $img->crop($offsetX, $offsetY, $width, $height);
        }

        return $this;
    }
}
