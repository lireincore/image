<?php

namespace LireinCore\Image\Effects;

use LireinCore\Image\EffectInterface;
use LireinCore\Image\ImageInterface;

/**
 * Image grayscale
 */
class Grayscale implements EffectInterface
{
    /**
     * @inheritdoc
     */
    public function apply(ImageInterface $img)
    {
        $img->grayscale();

        return $this;
    }
}
