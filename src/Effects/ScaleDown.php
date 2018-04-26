<?php

namespace LireinCore\Image\Effects;

use LireinCore\Image\EffectInterface;
use LireinCore\Image\TPixel;
use LireinCore\Image\ImageInterface;

/**
 * ScaleDown image
 */
class ScaleDown implements EffectInterface
{
    use TPixel;

    /**
     * @var string
     */
    protected $_minWidth;

    /**
     * @var string
     */
    protected $_minHeight;

    /**
     * @var bool
     */
    protected $_allowDecrease;

    /**
     * ScaleDown constructor.
     *
     * @param string $min_width for example: 100 | 20% (default: auto)
     * @param string $min_height for example: 100 | 20% (default: auto)
     * @param bool   $allow_decrease decrease if image is greater (default: false)
     */
    public function __construct($min_width = null, $min_height = null, $allow_decrease = false)
    {
        $this->_minWidth = $min_width;
        $this->_minHeight = $min_height;
        $this->_allowDecrease = $allow_decrease;
    }

    /**
     * @inheritdoc
     */
    public function apply(ImageInterface $img)
    {
        if ($this->_minWidth !== null || $this->_minHeight !== null) {
            $origWidth = $img->getWidth();
            $origHeight = $img->getHeight();
            $origAspectRatio = $origHeight / $origWidth;

            if ($this->_minWidth === null) {
                $minHeight = $this->getPxSize($this->_minHeight, $origHeight);
                $minWidth = (int)round($minHeight / $origAspectRatio);
            } elseif ($this->_minHeight === null) {
                $minWidth = $this->getPxSize($this->_minWidth, $origWidth);
                $minHeight = (int)round($minWidth * $origAspectRatio);
            } else {
                $minWidth = $this->getPxSize($this->_minWidth, $origWidth);
                $minHeight = $this->getPxSize($this->_minHeight, $origHeight);
            }

            $aspectRatio = $minHeight / $minWidth;

            if ($aspectRatio < $origAspectRatio) {
                if ($origHeight < $minHeight || ($origHeight > $minHeight && $this->_allowDecrease)) {
                    $newHeight = $minHeight;
                    $newWidth = (int)round($newHeight / $origAspectRatio);
                    $img->resize($newWidth, $newHeight/*, $filter*/);
                }
            } else {
                if ($origWidth < $minWidth || ($origWidth > $minWidth && $this->_allowDecrease)) {
                    $newWidth = $minWidth;
                    $newHeight = (int)round($newWidth * $origAspectRatio);
                    $img->resize($newWidth, $newHeight/*, $filter*/);
                }
            }
        }

        return $this;
    }
}