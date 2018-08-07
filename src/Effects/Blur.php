<?php

namespace LireinCore\Image\Effects;

use LireinCore\Image\Effect;
use LireinCore\Image\Manipulator;

/**
 * Image gaussian blur
 */
class Blur implements Effect
{
    /**
     * @var float
     */
    protected $sigma;

    /**
     * Blur constructor.
     *
     * @param float $sigma for example: 1.0
     */
    public function __construct($sigma)
    {
        $this->sigma = $sigma;
    }

    /**
     * @inheritdoc
     */
    public function apply(Manipulator $manipulator)
    {
        $manipulator->blur($this->sigma);

        return $this;
    }
}