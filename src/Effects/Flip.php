<?php

namespace LireinCore\Image\Effects;

use LireinCore\Image\EffectInterface;
use LireinCore\Image\ImageInterface;

/**
 * Flip image
 */
class Flip implements EffectInterface
{
    const MODE_VERTICAL = 'vertical';
    const MODE_HORIZONTAL = 'horizontal';
    const MODE_FULL = 'full';

    /**
     * @var string
     */
    protected $_mode;

    /**
     * Flip constructor.
     *
     * @param string $mode for example: vertical, horizontal, full
     */
    public function __construct($mode)
    {
        $this->_mode = $mode;
    }

    /**
     * @inheritdoc
     */
    public function apply(ImageInterface $img)
    {
        if ($this->_mode === static::MODE_VERTICAL) {
            $img->flipVertically();
        } elseif ($this->_mode === static::MODE_HORIZONTAL) {
            $img->flipHorizontally();
        } elseif ($this->_mode === static::MODE_FULL) {
            $img->flipHorizontally();
            $img->flipVertically();
        }
        
        return $this;
    }
}