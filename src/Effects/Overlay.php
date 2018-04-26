<?php

namespace LireinCore\Image\Effects;

use LireinCore\Image\EffectInterface;
use LireinCore\Image\TPixel;
use LireinCore\Image\ImageInterface;

/**
 * Overlay image
 */
class Overlay implements EffectInterface
{
    use TPixel;

    /**
     * @var string
     */
    protected $_path;

    /**
     * @var int
     */
    protected $_opacity;

    /**
     * @var string
     */
    protected $_offsetX;

    /**
     * @var string
     */
    protected $_offsetY;

    /**
     * @var string
     */
    protected $_width;

    /**
     * @var string
     */
    protected $_height;

    /**
     * Overlay constructor.
     *
     * @param string $path absolute path to the overlay image
     * @param int    $opacity for example: 0-100, 0 - fully transparent | 100 - not transparent (default: 100)
     * @param string $offset_x for example: 100 | 20% | center | left | right (default: right)
     * @param string $offset_y for example: 100 | 20% | center | top | bottom (default: bottom)
     * @param string $width for example: 100 | 20% - change overlay image width (% - relative to the background image) (default: original size)
     * @param string $height for example: 100 | 20% - change overlay image height (% - relative to the background image) (default: original size)
     */
    public function __construct($path, $opacity = 100, $offset_x = 'right', $offset_y = 'bottom', $width = null, $height = null)
    {
        $this->_path = $path;
        $this->_opacity = $opacity;
        $this->_offsetX = $offset_x;
        $this->_offsetY = $offset_y;
        $this->_width = $width;
        $this->_height = $height;
    }

    /**
     * @inheritdoc
     */
    public function apply(ImageInterface $img)
    {
        $origWidth = $img->getWidth();
        $origHeight = $img->getHeight();

        $watermark = $img::newInstance($img->getDriver(), false)->open($this->_path);

        $wmOrigWidth = $watermark->getWidth();
        $wmOrigHeight = $watermark->getHeight();

        $wmWidth = $this->_width === null ? $wmOrigWidth : $this->getPxSize($this->_width, $origWidth);
        $wmHeight = $this->_height === null ? $wmOrigHeight : $this->getPxSize($this->_height, $origHeight);

        $watermark->resize($wmWidth, $wmHeight);

        if ($wmWidth > $origWidth || $wmHeight > $origHeight) {
            $watermark->apply(new ScaleUp($origWidth, $origHeight));
        }

        $wmNewWidth = $watermark->getWidth();
        $wmNewHeight = $watermark->getHeight();

        $offsetX = $this->getWtOffset($this->_offsetX, $origWidth, $wmNewWidth);
        $offsetY = $this->getWtOffset($this->_offsetY, $origHeight, $wmNewHeight);

        $img->paste($watermark, $offsetX, $offsetY, $this->_opacity);

        return $this;
    }
}