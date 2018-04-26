<?php

namespace LireinCore\Image\Effects;

use LireinCore\Image\EffectInterface;
use LireinCore\Image\TPixel;
use LireinCore\Image\ImageInterface;

/**
 * ScaleUp image
 */
class ScaleUp implements EffectInterface
{
    use TPixel;

    /**
     * @var string
     */
    protected $_maxWidth;

    /**
     * @var string
     */
    protected $_maxHeight;

    /**
     * @var bool
     */
    protected $_allowIncrease;

    /**
     * ScaleUp constructor.
     *
     * @param string $max_width for example: 100 | 20% (default: auto)
     * @param string $max_height for example: 100 | 20% (default: auto)
     * @param bool   $allow_increase increase if image is less (default: false)
     */
    public function __construct($max_width = null, $max_height = null, $allow_increase = false)
    {
        $this->_maxWidth = $max_width;
        $this->_maxHeight = $max_height;
        $this->_allowIncrease = $allow_increase;
    }

    /**
     * @inheritdoc
     */
    public function apply(ImageInterface $img)
    {
        if ($this->_maxWidth !== null || $this->_maxHeight !== null) {
            $origWidth = $img->getWidth();
            $origHeight = $img->getHeight();
            $origAspectRatio = $origHeight / $origWidth;

            if ($this->_maxWidth === null) {
                $maxHeight = $this->getPxSize($this->_maxHeight, $origHeight);
                $maxWidth = (int)round($maxHeight / $origAspectRatio);
            } elseif ($this->_maxHeight === null) {
                $maxWidth = $this->getPxSize($this->_maxWidth, $origWidth);
                $maxHeight = (int)round($maxWidth * $origAspectRatio);
            } else {
                $maxWidth = $this->getPxSize($this->_maxWidth, $origWidth);
                $maxHeight = $this->getPxSize($this->_maxHeight, $origHeight);
            }

            $aspectRatio = $maxHeight / $maxWidth;

            if ($aspectRatio < $origAspectRatio) {
                if ($origHeight > $maxHeight || ($origHeight < $maxHeight && $this->_allowIncrease)) {
                    $newHeight = $maxHeight;
                    $newWidth = (int)round($newHeight / $origAspectRatio);
                    $img->resize($newWidth, $newHeight/*, $filter*/);
                }
            } else {
                if ($origWidth > $maxWidth || ($origWidth < $maxWidth && $this->_allowIncrease)) {
                    $newWidth = $maxWidth;
                    $newHeight = (int)round($newWidth * $origAspectRatio);
                    $img->resize($newWidth, $newHeight/*, $filter*/);
                }
            }
        }

        return $this;
    }
}