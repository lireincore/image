<?php

namespace LireinCore\Image\Effects;

use LireinCore\Image\Effect;
use LireinCore\Image\Manipulator;

/**
 * Image grayscale
 */
final class Grayscale implements Effect
{
    /**
     * @inheritdoc
     */
    public function apply(Manipulator $manipulator) : Effect
    {
        $manipulator->grayscale();

        return $this;
    }
}