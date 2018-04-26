<?php

namespace LireinCore\Image;

interface PostProcessorInterface
{
    /**
     * @return array
     */
    public function getSupportedFormats();

    /**
     * @param string $path
     * @return $this
     * @throws \RuntimeException
     */
    public function process($path);
}