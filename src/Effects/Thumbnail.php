<?php

namespace LireinCore\Image\Effects;

use LireinCore\Image\Pixel;
use LireinCore\Image\Effect;
use LireinCore\Image\Manipulator;

/**
 * Image thumbnail
 */
class Thumbnail implements Effect
{
    use Pixel;

    const MODE_INSET = 'inset';
    const MODE_OUTBOUND = 'outbound';

    /**
     * @var string
     */
    protected $width;

    /**
     * @var string
     */
    protected $height;

    /**
     * @var string
     */
    protected $mode;

    /**
     * @var string
     */
    protected $bgColor;

    /**
     * @var int
     */
    protected $bgTransparency;

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
        $this->width = $width;
        $this->height = $height;
        $this->mode = $mode;
        $this->bgColor = $bgcolor;
        $this->bgTransparency = $bgtransparency;
    }

    /**
     * @inheritdoc
     */
    public function apply(Manipulator $manipulator)
    {
        if ($this->mode === static::MODE_INSET) {
            $manipulator->apply(new ScaleUp($this->width, $this->height));
        } elseif ($this->mode === static::MODE_OUTBOUND) {
            $manipulator->apply(new Cover('center', 'center', $this->width, $this->height));
        }

        return $this;
    }
}