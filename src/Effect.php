<?php

namespace LireinCore\Image;

interface Effect
{
    /**
     * @param Manipulator $manipulator
     * @return $this
     */
    public function apply(Manipulator $manipulator);
}