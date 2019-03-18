<?php

namespace LireinCore\Image\Effects;

use LireinCore\Image\Pixel;
use LireinCore\Image\Effect;
use LireinCore\Image\Manipulator;

/**
 * Rotate image
 */
final class Rotate implements Effect
{
    use Pixel;

    /**
     * @var int
     */
    private $angle;

    /**
     * @var string
     */
    private $bgColor;

    /**
     * @var int
     */
    private $bgTransparency;

    /**
     * Rotate constructor.
     *
     * @param int    $angle in degrees for example: 90
     * @param string $bgcolor for example: '#fff' or '#ffffff' - hex | '50,50,50' - rgb | '50,50,50,50' - cmyk (default: #fff)
     * @param int    $bgtransparency for example: 0-100, 0 - not transparent | 100 - fully transparent (default: 0)
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
    public function apply(Manipulator $manipulator) : Effect
    {
        $bgColor = $this->parseColor($this->bgColor);
        $manipulator->rotate($this->angle, $bgColor, $this->bgTransparency);
        
        return $this;
    }
}