<?php

namespace LireinCore\Image\Effects;

use LireinCore\Image\Pixel;
use LireinCore\Image\Effect;
use LireinCore\Image\Manipulator;

/**
 * Crop image
 */
final class Crop implements Effect
{
    use Pixel;

    /**
     * @var string
     */
    private $offsetX;

    /**
     * @var string
     */
    private $offsetY;

    /**
     * @var string|null
     */
    private $width;

    /**
     * @var string|null
     */
    private $height;

    /**
     * Crop constructor.
     *
     * @param string      $offset_x for example: 100 | 20% | center | left | right (default: left)
     * @param string      $offset_y for example: 100 | 20% | center | top | bottom (default: top)
     * @param string|null $width for example: 100 | 20% (default: auto)
     * @param string|null $height for example: 100 | 20% (default: auto)
     */
    public function __construct($offset_x = 'left', $offset_y = 'top', $width = null, $height = null)
    {
        $this->offsetX = $offset_x;
        $this->offsetY = $offset_y;
        $this->width = $width;
        $this->height = $height;
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

            $offsetX = $this->pxOffset($this->offsetX, $origWidth, $width);
            $offsetY = $this->pxOffset($this->offsetY, $origHeight, $height);

            $manipulator->crop($offsetX, $offsetY, $width, $height);
        }

        return $this;
    }
}