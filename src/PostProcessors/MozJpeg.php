<?php

namespace LireinCore\Image\PostProcessors;

use LireinCore\Image\PostProcessor;

/**
 * MozJpeg image postprocessor
 */
final class MozJpeg implements PostProcessor
{
    /**
     * @var string
     */
    private $path; //cjpeg //jpegtran

    /**
     * @var int
     */
    private $quality;

    /**
     * @var string[]
     */
    private $supportedFormats = ['jpeg'];

    /**
     * MozJpeg constructor.
     *
     * @param string $path path to postprocessor binary (default: '/opt/mozjpeg/bin/cjpeg')
     * @param int    $quality
     */
    public function __construct($path = '/opt/mozjpeg/bin/cjpeg', $quality = 70)
    {
        $this->path = $path;
        $this->quality = $quality;
    }

    /**
     * @inheritdoc
     */
    public function supportedFormats() : array
    {
        return $this->supportedFormats;
    }

    /**
     * @inheritdoc
     */
    public function process(string $path) : PostProcessor
    {
        //todo: not implemented
        //$stripAll = $this->_stripAll ? ' -strip all' : '';
        //$cmd = "{$this->_path}{$stripAll} -clobber -preserve -quiet -o{$this->_level} {$path}";
        //$this->exec($cmd);

        return $this;
    }

    /**
     * @param string $cmd
     * @throws \RuntimeException
     */
    private function exec(string $cmd) : void
    {
        $out = [];
        $result = null;
        exec($cmd, $out, $result);

        if ($result !== 0) {
            $error = sprintf('The command "%s" failed. Exit code: %s', $cmd, $result);
            throw new \RuntimeException($error);
        }
    }
}