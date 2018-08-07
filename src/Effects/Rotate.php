<?php

namespace LireinCore\Image\Effects;

use LireinCore\Image\Pixel;
use LireinCore\Image\Effect;
use LireinCore\Image\Manipulator;

/**
 * Rotate image
 */
class Rotate implements Effect
{
    use Pixel;

    /**
     * @var float|int
     */
    protected $angle;

    /**
     * @var string
     */
    protected $bgColor;

    /**
     * @var int
     */
    protected $bgTransparency;

    /**
     * Rotate constructor.
     *
     * @param float|int $angle in degrees for example: 90
     * @param string    $bgcolor for example: '#fff' or '#ffffff' - hex | '50,50,50' - rgb | '50,50,50,50' - cmyk (default: #fff)
     * @param int       $bgtransparency for example: 0-100, 0 - not transparent | 100 - fully transparent (default: 0)
     */
    public function __construct($angle, $bgcolor = '#fff', $bgtransparency = 0)
    {
        $this->angle = $angle;
        $this->bgColor = $bgcolor;
        $this->bgTransparency = $bgtransparency;
    }

    /**
     * @inheritdoc
     */
    public function apply(Manipulator $manipulator)
    {
        $bgColor = $this->parseColor($this->bgColor);
        $manipulator->rotate($this->angle, $bgColor, $this->bgTransparency);
        
        return $this;
    }
}