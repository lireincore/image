<?php

namespace LireinCore\Image\Effects;

use LireinCore\Image\EffectInterface;
use LireinCore\Image\ImageInterface;

/**
 * Image gamma correction
 */
class Gamma implements EffectInterface
{
    /**
     * @var float
     */
    protected $_correction;

    /**
     * Gamma constructor.
     *
     * @param float $correction for example: 0.7
     */
    public function __construct($correction)
    {
        $this->_correction = $correction;
    }

    /**
     * @inheritdoc
     */
    public function apply(ImageInterface $img)
    {
        $img->gamma($this->_correction);

        return $this;
    }
}