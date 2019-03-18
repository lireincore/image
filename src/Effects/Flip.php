<?php

namespace LireinCore\Image\Effects;

use LireinCore\Image\Effect;
use LireinCore\Image\Manipulator;

/**
 * Flip image
 */
final class Flip implements Effect
{
    public const MODE_VERTICAL = 'vertical';
    public const MODE_HORIZONTAL = 'horizontal';
    public const MODE_FULL = 'full';

    /**
     * @var string
     */
    private $mode;

    /**
     * Flip constructor.
     *
     * @param string $mode for example: vertical, horizontal, full
     */
    public function __construct($mode)
    {
        $this->mode = $mode;
    }

    /**
     * @inheritdoc
     */
    public function apply(Manipulator $manipulator) : Effect
    {
        if ($this->mode === static::MODE_VERTICAL) {
            $manipulator->flipVertically();
        } elseif ($this->mode === static::MODE_HORIZONTAL) {
            $manipulator->flipHorizontally();
        } elseif ($this->mode === static::MODE_FULL) {
            $manipulator->flipHorizontally();
            $manipulator->flipVertically();
        }
        
        return $this;
    }
}