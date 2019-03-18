<?php

namespace LireinCore\Image\Effects;

use LireinCore\Image\Pixel;
use LireinCore\Image\Effect;
use LireinCore\Image\Manipulator;

/**
 * ScaleUp image
 */
final class ScaleUp implements Effect
{
    use Pixel;

    /**
     * @var string|null
     */
    private $maxWidth;

    /**
     * @var string|null
     */
    private $maxHeight;

    /**
     * @var bool
     */
    private $allowIncrease;

    /**
     * ScaleUp constructor.
     *
     * @param string|null $max_width for example: 100 | 20% (default: auto)
     * @param string|null $max_height for example: 100 | 20% (default: auto)
     * @param bool        $allow_increase increase if image is less (default: false)
     */
    public function __construct($max_width = null, $max_height = null, $allow_increase = false)
    {
        $this->maxWidth = $max_width;
        $this->maxHeight = $max_height;
        $this->allowIncrease = $allow_increase;
    }

    /**
     * @inheritdoc
     */
    public function apply(Manipulator $manipulator) : Effect
    {
        if ($this->maxWidth !== null || $this->maxHeight !== null) {
            $origWidth = $manipulator->width();
            $origHeight = $manipulator->height();
            $origAspectRatio = $origHeight / $origWidth;

            if ($this->maxWidth === null) {
                $maxHeight = $this->pxSize($this->maxHeight, $origHeight);
                $maxWidth = (int)round($maxHeight / $origAspectRatio);
            } elseif ($this->maxHeight === null) {
                $maxWidth = $this->pxSize($this->maxWidth, $origWidth);
                $maxHeight = (int)round($maxWidth * $origAspectRatio);
            } else {
                $maxWidth = $this->pxSize($this->maxWidth, $origWidth);
                $maxHeight = $this->pxSize($this->maxHeight, $origHeight);
            }

            $aspectRatio = $maxHeight / $maxWidth;

            if ($aspectRatio < $origAspectRatio) {
                if ($origHeight > $maxHeight || ($origHeight < $maxHeight && $this->allowIncrease)) {
                    $newHeight = $maxHeight;
                    $newWidth = (int)round($newHeight / $origAspectRatio);
                    $manipulator->resize($newWidth, $newHeight/*, $filter*/); //todo
                }
            } else {
                if ($origWidth > $maxWidth || ($origWidth < $maxWidth && $this->allowIncrease)) {
                    $newWidth = $maxWidth;
                    $newHeight = (int)round($newWidth * $origAspectRatio);
                    $manipulator->resize($newWidth, $newHeight/*, $filter*/); //todo
                }
            }
        }

        return $this;
    }
}