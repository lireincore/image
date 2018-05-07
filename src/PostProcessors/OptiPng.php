<?php

namespace LireinCore\Image\PostProcessors;

use LireinCore\Image\PostProcessorInterface;

/**
 * OptiPng image postprocessor
 */
class OptiPng implements PostProcessorInterface
{
    /**
     * @var string
     */
    protected $_path;

    /**
     * @var int
     */
    protected $_level;

    /**
     * @var bool
     */
    protected $_stripAll;

    /**
     * @var bool
     */
    protected $_supportedFormats = ['png']; //todo: also supported convert to 'bmp', 'gif', 'pnm', 'tiff'

    /**
     * OptiPng constructor.
     *
     * @param string $path path to postprocessor binary (default: '/usr/bin/optipng')
     * @param int $level for example: 0-7, 0 - maximum compression speed | 7 - maximum compression size (default: 2)
     * @param bool $strip_all remove all metadata (default: true)
     */
    public function __construct($path = '/usr/bin/optipng', $level = 2, $strip_all = true)
    {
        $this->_path = $path;
        $this->_level = $level;
        $this->_stripAll = $strip_all;
    }

    /**
     * @inheritdoc
     */
    public function getSupportedFormats()
    {
        return $this->_supportedFormats;
    }

    /**
     * @inheritdoc
     */
    public function process($path)
    {
        $stripAll = $this->_stripAll ? ' -strip all' : '';
        $cmd = "{$this->_path}{$stripAll} -clobber -preserve -quiet -o{$this->_level} {$path}";
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

        if ($result !== 0) {
            $error = sprintf('The command "%s" failed. Exit code: %s', $cmd, $result);
            throw new \RuntimeException($error);
        }
    }
}