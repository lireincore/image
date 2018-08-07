<?php

namespace LireinCore\Image\Effects;

use LireinCore\Image\Effect;
use LireinCore\Image\Manipulator;

/**
 * Image negative
 */
class Negative implements Effect
{
    /**
     * @inheritdoc
     */
    public function apply(Manipulator $manipulator)
    {
        $manipulator->negative();
        
        return $this;
    }
}