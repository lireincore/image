<?php

namespace LireinCore\Image\Effects;

use LireinCore\Image\EffectInterface;
use LireinCore\Image\ImageInterface;

/**
 * Image gaussian blur
 */
class Blur implements EffectInterface
{
    /**
     * @var float
     */
    protected $_sigma;

    /**
     * Blur constructor.
     *
     * @param float $sigma for example: 1.0
     */
    public function __construct($sigma)
    {
        $this->_sigma = $sigma;
    }

    /**
     * @inheritdoc
     */
    public function apply(ImageInterface $img)
    {
        $img->blur($this->_sigma);

        return $this;
    }
}
