<?php

namespace LireinCore\Image\Effects;

use LireinCore\Image\EffectInterface;
use LireinCore\Image\ImageInterface;
use LireinCore\Image\TPixel;

/**
 * Image text
 */
class Text implements EffectInterface
{
    use TPixel;

    /**
     * @var string
     */
    protected $_text;

    /**
     * @var string
     */
    protected $_font;

    /**
     * @var string
     */
    protected $_offsetX;

    /**
     * @var string
     */
    protected $_offsetY;

    /**
     * @var int
     */
    protected $_size;

    /**
     * @var string
     */
    protected $_color;

    /**
     * @var int
     */
    protected $_opacity;

    /**
     * @var float|int
     */
    protected $_angle;

    /**
     * @var string
     */
    protected $_width;

    /**
     * Text constructor.
     *
     * @param string    $text text for writing
     * @param string    $font font name or absolute path to the font file, for example: Verdana (default: Times New Roman)
     * @param string    $offset_x for example: 100 | 20% (default: 0)
     * @param string    $offset_y for example: 100 | 20% (default: 0)
     * @param int       $size font size for example: 14 (default: 12)
     * @param string    $color font color for example: '#fff' or '#ffffff' - hex | '50,50,50' - rgb | '50,50,50,50' - cmyk (default: #fff)
     * @param int       $opacity for example: 0-100, 0 - fully transparent | 100 - not transparent (default: 100)
     * @param float|int $angle in degrees for example: 90 (default: 0)
     * @param string    $width for example: 100 | 20% - text box width (% - relative to the background image) (default: none)
     */
    public function __construct($text, $font = 'Times New Roman', $offset_x = '0', $offset_y = '0', $size = 12, $color = '#fff', $opacity = 100, $angle = 0, $width = null)
    {
        $this->_text = $text;
        $this->_font = $font;
        $this->_offsetX = $offset_x;
        $this->_offsetY = $offset_y;
        $this->_size = $size;
        $this->_color = $color;
        $this->_opacity = $opacity;
        $this->_angle = $angle;
        $this->_width = $width;
    }

    /**
     * @inheritdoc
     */
    public function apply(ImageInterface $img)
    {
        $origWidth = $img->getWidth();
        $origHeight = $img->getHeight();
        $offsetX = $this->getPxSize($this->_offsetX, $origWidth);
        $offsetY = $this->getPxSize($this->_offsetY, $origHeight);
        $color = $this->parseColor($this->_color);
        $width = $this->_width !== null ? $this->getPxSize($this->_width, $origWidth) : null;

        $img->text($this->_text, $this->_font, $offsetX, $offsetY, $this->_size, $color, $this->_opacity, $this->_angle, $width);

        return $this;
    }
}
