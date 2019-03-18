<?php

namespace LireinCore\Image\Effects;

use LireinCore\Image\Pixel;
use LireinCore\Image\Effect;
use LireinCore\Image\Manipulator;

/**
 * Cover image
 */
final class Cover implements Effect
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
     * Cover constructor.
     *
     * @param string      $offset_x for example: 100 | 20% | center | left | right (default: center)
     * @param string      $offset_y for example: 100 | 20% | center | top | bottom (default: center)
     * @param string|null $width for example: 100 | 20% (default: auto)
     * @param string|null $height for example: 100 | 20% (default: auto)
     */
    public function __construct($offset_x = 'center', $offset_y = 'center', $width = null, $height = null)
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

            if ($origWidth !== $width || $origHeight !== $height) {
                $manipulator->apply(new ScaleDown($width, $height, true));
                $newWidth = $manipulator->width();
                $newHeight = $manipulator->height();
                if ($newWidth !== $width || $newHeight !== $height) {
                    $offsetX = $this->wtOffset($this->offsetX, $manipulator->width(), $width);
                    $offsetY = $this->wtOffset($this->offsetY, $manipulator->height(), $height);
                    $manipulator->crop($offsetX, $offsetY, $width, $height);
                }
            }
        }

        return $this;
    }
}