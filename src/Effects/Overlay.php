<?php

namespace LireinCore\Image\Effects;

use LireinCore\Image\Pixel;
use LireinCore\Image\Effect;
use LireinCore\Image\Manipulator;

/**
 * Overlay image
 */
class Overlay implements Effect
{
    use Pixel;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var int
     */
    protected $opacity;

    /**
     * @var string
     */
    protected $offsetX;

    /**
     * @var string
     */
    protected $offsetY;

    /**
     * @var string
     */
    protected $width;

    /**
     * @var string
     */
    protected $height;

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
        $this->path = $path;
        $this->opacity = $opacity;
        $this->offsetX = $offset_x;
        $this->offsetY = $offset_y;
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * @inheritdoc
     */
    public function apply(Manipulator $manipulator)
    {
        $origWidth = $manipulator->width();
        $origHeight = $manipulator->height();

        $wmManipulator = $manipulator->instance()->open($this->path);

        $wmOrigWidth = $wmManipulator->width();
        $wmOrigHeight = $wmManipulator->height();

        $wmWidth = $this->width === null ? $wmOrigWidth : $this->pxSize($this->width, $origWidth);
        $wmHeight = $this->height === null ? $wmOrigHeight : $this->pxSize($this->height, $origHeight);

        $wmManipulator->resize($wmWidth, $wmHeight);

        if ($wmWidth > $origWidth || $wmHeight > $origHeight) {
            $wmManipulator->apply(new ScaleUp($origWidth, $origHeight));
        }

        $wmNewWidth = $wmManipulator->width();
        $wmNewHeight = $wmManipulator->height();

        $offsetX = $this->wtOffset($this->offsetX, $origWidth, $wmNewWidth);
        $offsetY = $this->wtOffset($this->offsetY, $origHeight, $wmNewHeight);

        $manipulator->paste($wmManipulator, $offsetX, $offsetY, $this->opacity);

        return $this;
    }
}