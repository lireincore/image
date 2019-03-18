<?php

namespace LireinCore\Image\Effects;

use LireinCore\Image\Pixel;
use LireinCore\Image\Effect;
use LireinCore\Image\Manipulator;

/**
 * Resize image
 */
final class Resize implements Effect
{
    use Pixel;

    /**
     * @var string|null
     */
    private $width;

    /**
     * @var string|null
     */
    private $height;

    /**
     * @var string|null
     */
    private $filter;

    /**
     * Resize constructor.
     * 
     * @param string|null $width for example: 100 | 20% (default: auto)
     * @param string|null $height for example: 100 | 20% (default: auto)
     * @param string|null $filter
     */
    public function __construct($width = null, $height = null, $filter = null)
    {
        $this->width = $width;
        $this->height = $height;
        $this->filter = $filter;
    }

    /**
     * @inheritdoc
     */
    public function apply(Manipulator $manipulator) : Effect
    {
        if ($this->width !== null || $this->height !== null) {
            $origWidth = $manipulator->width();
            $origHeight = $manipulator->height();
            $origAspectRatio = $origHeight / $origWidth;

            if ($this->width === null) {
                $height = $this->pxSize($this->height, $origHeight);
                $width = (int)round($height / $origAspectRatio);
            } elseif ($this->height === null) {
                $width = $this->pxSize($this->width, $origWidth);
                $height = (int)round($width * $origAspectRatio);
            } else {
                $width = $this->pxSize($this->width, $origWidth);
                $height = $this->pxSize($this->height, $origHeight);
            }

            $manipulator->resize($width, $height/*, $this->filter*/); //todo
        }

        return $this;
    }
}