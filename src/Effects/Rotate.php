<?php

namespace LireinCore\Image\Effects;

use LireinCore\Image\EffectInterface;
use LireinCore\Image\TPixel;
use LireinCore\Image\ImageInterface;

/**
 * Rotate image
 */
class Rotate implements EffectInterface
{
    use TPixel;

    /**
     * @var float|int
     */
    protected $_angle;

    /**
     * @var string
     */
    protected $_bgColor;

    /**
     * @var int
     */
    protected $_bgTransparency;

    /**
     * Rotate constructor.
     *
     * @param float|int $angle in degrees for example: 90
     * @param string    $bgcolor for example: '#fff' or '#ffffff' - hex | '50,50,50' - rgb | '50,50,50,50' - cmyk (default: #fff)
     * @param int       $bgtransparency for example: 0-100, 0 - not transparent | 100 - fully transparent (default: 0)
     */
    public function __construct($angle, $bgcolor = '#fff', $bgtransparency = 0)
    {
        $this->_angle = $angle;
        $this->_bgColor = $bgcolor;
        $this->_bgTransparency = $bgtransparency;
    }

    /**
     * @inheritdoc
     */
    public function apply(ImageInterface $img)
    {
        $bgColor = $this->parseColor($this->_bgColor);
        $img->rotate($this->_angle, $bgColor, $this->_bgTransparency);
        
        return $this;
    }
}