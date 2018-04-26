<?php

namespace LireinCore\Image;

interface EffectInterface
{
    /**
     * @param ImageInterface $img
     * @return $this
     */
    public function apply(ImageInterface $img);
}