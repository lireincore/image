<?php

namespace LireinCore\Image\Effects;

use LireinCore\Image\EffectInterface;
use LireinCore\Image\ImageInterface;

/**
 * Image negative
 */
class Negative implements EffectInterface
{
    /**
     * @inheritdoc
     */
    public function apply(ImageInterface $img)
    {
        $img->negative();
        
        return $this;
    }
}