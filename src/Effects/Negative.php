<?php

namespace LireinCore\Image\Effects;

use LireinCore\Image\Effect;
use LireinCore\Image\Manipulator;

/**
 * Image negative
 */
final class Negative implements Effect
{
    /**
     * @inheritdoc
     */
    public function apply(Manipulator $manipulator) : Effect
    {
        $manipulator->negative();
        
        return $this;
    }
}