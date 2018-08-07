<?php

namespace LireinCore\Image\PostProcessors;

use LireinCore\Image\PostProcessor;

/**
 * PngQuant image postprocessor
 */
class PngQuant implements PostProcessor
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @var int
     */
    protected $quality;

    /**
     * @var bool
     */
    protected $supportedFormats = ['png'];

    /**
     * PngQuant constructor.
     *
     * @param string $path path to postprocessor binary (default: '/usr/bin/pngquant')
     * @param int $quality for example: 0-100, 0 - worst | 100 - best (default: 85)
     */
    public function __construct($path = '/usr/bin/pngquant', $quality = 85)
    {
        $this->path = $path;
        $this->quality = $quality;
    }

    /**
     * @inheritdoc
     */
    public function supportedFormats()
    {
        return $this->supportedFormats;
    }

    /**
     * @inheritdoc
     */
    public function process($path)
    {
        //todo
        //--speed N         speed/quality trade-off. 1=slow, 3=default, 11=fast & rough
        //--nofs            disable Floyd-Steinberg dithering
        //--posterize N     output lower resolution color (e.g. for ARGB4444 output)

        $cmd = "{$this->path} -f --skip-if-larger --quality {$this->quality} -o {$path} {$path}";
        $this->exec($cmd);

        return $this;
    }

    /**
     * @param string $cmd
     * @throws \RuntimeException
     */
    protected function exec($cmd)
    {
        $out = [];
        $result = null;
        exec($cmd, $out, $result);

        if ($result !== 0 && $result !== 98 && $result !== 99) {
            $error = sprintf('The command "%s" failed. Exit code: %s', $cmd, $result);
            throw new \RuntimeException($error);
        }
    }
}