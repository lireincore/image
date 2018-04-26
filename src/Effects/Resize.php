<?php

namespace LireinCore\Image\Effects;

use LireinCore\Image\EffectInterface;
use LireinCore\Image\TPixel;
use LireinCore\Image\ImageInterface;

/**
 * Resize image
 */
class Resize implements EffectInterface
{
    use TPixel;

    /**
     * @var string
     */
    protected $_width;

    /**
     * @var string
     */
    protected $_height;

    /**
     * @var string
     */
    protected $_filter;

    /**
     * Resize constructor.
     * 
     * @param string $width for example: 100 | 20% (default: auto)
     * @param string $height for example: 100 | 20% (default: auto)
     * @param string $filter
     */
    public function __construct($width = null, $height = null, $filter = null)
    {
        $this->_width = $width;
        $this->_height = $height;
        $this->_filter = $filter;
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

            $img->resize($width, $height/*, $this->_filter*/);
        }

        return $this;
    }
}