<?php

namespace LireinCore\Image\Effects;

use LireinCore\Image\EffectInterface;
use LireinCore\Image\TPixel;
use LireinCore\Image\ImageInterface;

/**
 * Fit image in box
 */
class Fit implements EffectInterface
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
     * @var string
     */
    protected $_bgColor;

    /**
     * @var int
     */
    protected $_bgTransparency;

    /**
     * @var bool
     */
    protected $_allowIncrease;

    /**
     * Fit constructor.
     *
     * @param string $offset_x for example: 100 | 20% | center | left | right (default: center)
     * @param string $offset_y for example: 100 | 20% | center | top | bottom (default: center)
     * @param string $width for example: 100 | 20% (default: auto)
     * @param string $height for example: 100 | 20% (default: auto)
     * @param string $bgcolor for example: '#fff' or '#ffffff' - hex | '50,50,50' - rgb | '50,50,50,50' - cmyk (default: #fff)
     * @param int    $bgtransparency for example: 0-100, 0 - not transparent | 100 - fully transparent (default: 0)
     * @param bool   $allow_increase increase if image is less (default: false)
     */
    public function __construct($offset_x = 'center', $offset_y = 'center', $width = null, $height = null, $bgcolor = '#fff', $bgtransparency = 0, $allow_increase = false)
    {
        $this->_offsetX = $offset_x;
        $this->_offsetY = $offset_y;
        $this->_width = $width;
        $this->_height = $height;
        $this->_bgColor = $bgcolor;
        $this->_bgTransparency = $bgtransparency;
        $this->_allowIncrease = $allow_increase;
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

            if ($origWidth !== $width || $origHeight !== $height) {
                $img->apply(new ScaleUp($width, $height, $this->_allowIncrease));
                $newWidth = $img->getWidth();
                $newHeight = $img->getHeight();
                if ($newWidth !== $width || $newHeight !== $height) {
                    $oldImage = $img->copy();
                    $bgColor = $this->parseColor($this->_bgColor);
                    $img->create($width, $height, $bgColor, $this->_bgTransparency);
                    $offsetX = $this->getWtOffset($this->_offsetX, $width, $oldImage->getWidth());
                    $offsetY = $this->getWtOffset($this->_offsetY, $height, $oldImage->getHeight());
                    $img->paste($oldImage, $offsetX, $offsetY);
                }
            }
        }

        return $this;
    }
}