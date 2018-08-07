<?php

namespace LireinCore\Image\Effects;

use LireinCore\Image\Effect;
use LireinCore\Image\Manipulator;

/**
 * Image grayscale
 */
class Grayscale implements Effect
{
    /**
     * @inheritdoc
     */
    public function apply(Manipulator $manipulator)
    {
        $manipulator->grayscale();

        return $this;
    }
}