<?php

namespace LireinCore\Image;

interface PostProcessor
{
    /**
     * @return string[]
     */
    public function supportedFormats() : array;

    /**
     * @param string $path
     * @return $this
     * @throws \RuntimeException
     */
    public function process(string $path) : self;
}