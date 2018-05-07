<?php

namespace LireinCore\Image\Effects;

use LireinCore\Image\EffectInterface;
use LireinCore\Image\TPixel;
use LireinCore\Image\ImageInterface;

/**
 * Image thumbnail
 */
class Thumbnail implements EffectInterface
{
    use TPixel;

    const MODE_INSET = 'inset';
    const MODE_OUTBOUND = 'outbound';

    /**
     * @var string
     */
    protected $_width;

    /**
     * @var string
     */
    protected $_height;

    /**
     * @var string
     */
    protected $_mode;

    /**
     * @var string
     */
    protected $_bgColor;

    /**
     * @var int
     */
    protected $_bgTransparency;

    /**
     * Thumbnail constructor.
     *
     * @param string $width for example: 100 | 20% (default: auto)
     * @param string $height for example: 100 | 20% (default: auto)
     * @param string $mode for example: inset | outbound (default: inset)
     * @param string $bgcolor for example: '#fff' or '#ffffff' - hex | '50,50,50' - rgb | '50,50,50,50' - cmyk (default: #fff)
     * @param int    $bgtransparency for example: 0 - not transparent | 100 - fully transparent (default: 0)
     */
    public function __construct($width = null, $height = null, $mode = self::MODE_INSET, $bgcolor = '#fff', $bgtransparency = 0)
    {
        $this->_width = $width;
        $this->_height = $height;
        $this->_mode = $mode;
        $this->_bgColor = $bgcolor;
        $this->_bgTransparency = $bgtransparency;
    }

    /**
     * @inheritdoc
     */
    public function apply(ImageInterface $img)
    {
        if ($this->_mode === static::MODE_INSET) {
            $img->apply(new ScaleUp($this->_width, $this->_height));
        } elseif ($this->_mode === static::MODE_OUTBOUND) {
            $img->apply(new Cover('center', 'center', $this->_width, $this->_height));
        }

        return $this;
    }
}
