<?php

namespace LireinCore\Image\Effects;

use LireinCore\Image\Pixel;
use LireinCore\Image\Effect;
use LireinCore\Image\Manipulator;

/**
 * ScaleDown image
 */
class ScaleDown implements Effect
{
    use Pixel;

    /**
     * @var string
     */
    protected $minWidth;

    /**
     * @var string
     */
    protected $minHeight;

    /**
     * @var bool
     */
    protected $allowDecrease;

    /**
     * ScaleDown constructor.
     *
     * @param string $min_width for example: 100 | 20% (default: auto)
     * @param string $min_height for example: 100 | 20% (default: auto)
     * @param bool   $allow_decrease decrease if image is greater (default: false)
     */
    public function __construct($min_width = null, $min_height = null, $allow_decrease = false)
    {
        $this->minWidth = $min_width;
        $this->minHeight = $min_height;
        $this->allowDecrease = $allow_decrease;
    }

    /**
     * @inheritdoc
     */
    public function apply(Manipulator $manipulator)
    {
        if ($this->minWidth !== null || $this->minHeight !== null) {
            $origWidth = $manipulator->width();
            $origHeight = $manipulator->height();
            $origAspectRatio = $origHeight / $origWidth;

            if ($this->minWidth === null) {
                $minHeight = $this->pxSize($this->minHeight, $origHeight);
                $minWidth = (int)round($minHeight / $origAspectRatio);
            } elseif ($this->minHeight === null) {
                $minWidth = $this->pxSize($this->minWidth, $origWidth);
                $minHeight = (int)round($minWidth * $origAspectRatio);
            } else {
                $minWidth = $this->pxSize($this->minWidth, $origWidth);
                $minHeight = $this->pxSize($this->minHeight, $origHeight);
            }

            $aspectRatio = $minHeight / $minWidth;

            if ($aspectRatio < $origAspectRatio) {
                if ($origHeight < $minHeight || ($origHeight > $minHeight && $this->allowDecrease)) {
                    $newHeight = $minHeight;
                    $newWidth = (int)round($newHeight / $origAspectRatio);
                    $manipulator->resize($newWidth, $newHeight/*, $filter*/);
                }
            } else {
                if ($origWidth < $minWidth || ($origWidth > $minWidth && $this->allowDecrease)) {
                    $newWidth = $minWidth;
                    $newHeight = (int)round($newWidth * $origAspectRatio);
                    $manipulator->resize($newWidth, $newHeight/*, $filter*/);
                }
            }
        }

        return $this;
    }
}