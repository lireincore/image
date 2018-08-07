<?php

namespace LireinCore\Image\Effects;

use LireinCore\Image\Pixel;
use LireinCore\Image\Effect;
use LireinCore\Image\Manipulator;

/**
 * Image text
 */
class Text implements Effect
{
    use Pixel;

    /**
     * @var string
     */
    protected $text;

    /**
     * @var string
     */
    protected $font;

    /**
     * @var string
     */
    protected $offsetX;

    /**
     * @var string
     */
    protected $offsetY;

    /**
     * @var int
     */
    protected $size;

    /**
     * @var string
     */
    protected $color;

    /**
     * @var int
     */
    protected $opacity;

    /**
     * @var float|int
     */
    protected $angle;

    /**
     * @var string
     */
    protected $width;

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
        $this->text = $text;
        $this->font = $font;
        $this->offsetX = $offset_x;
        $this->offsetY = $offset_y;
        $this->size = $size;
        $this->color = $color;
        $this->opacity = $opacity;
        $this->angle = $angle;
        $this->width = $width;
    }

    /**
     * @inheritdoc
     */
    public function apply(Manipulator $manipulator)
    {
        $origWidth = $manipulator->width();
        $origHeight = $manipulator->height();
        $offsetX = $this->pxSize($this->offsetX, $origWidth);
        $offsetY = $this->pxSize($this->offsetY, $origHeight);
        $color = $this->parseColor($this->color);
        $width = $this->width !== null ? $this->pxSize($this->width, $origWidth) : null;

        $manipulator->text($this->text, $this->font, $offsetX, $offsetY, $this->size, $color, $this->opacity, $this->angle, $width);

        return $this;
    }
}