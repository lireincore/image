<?php

namespace LireinCore\Image;

interface PostProcessor
{
    /**
     * @return array
     */
    public function supportedFormats();

    /**
     * @param string $path
     * @return $this
     * @throws \RuntimeException
     */
    public function process($path);
}