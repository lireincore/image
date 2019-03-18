<?php

namespace LireinCore\Image\Effects;

use LireinCore\Image\Effect;
use LireinCore\Image\Manipulator;

/**
 * Image gamma correction
 */
final class Gamma implements Effect
{
    /**
     * @var float
     */
    private $correction;

    /**
     * Gamma constructor.
     *
     * @param float $correction for example: 0.7
     */
    public function __construct($correction)
    {
        $this->correction = $correction;
    }

    /**
     * @inheritdoc
     */
    public function apply(Manipulator $manipulator) : Effect
    {
        $manipulator->gamma($this->correction);

        return $this;
    }
}