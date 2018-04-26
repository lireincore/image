<?php

namespace LireinCore\Image\PostProcessors;

use LireinCore\Image\PostProcessorInterface;

/**
 * MozJpeg image postprocessor
 */
class MozJpeg implements PostProcessorInterface
{
    const SUPPORTED_FORMATS = ['jpeg'];

    /**
     * @var string
     */
    protected $_path; //cjpeg //jpegtran

    /**
     * @var int
     */
    protected $_quality;

    /**
     * MozJpeg constructor.
     *
     * @param string $path path to postprocessor binary (default: '/opt/mozjpeg/bin/cjpeg')
     * @param int $quality
     */
    public function __construct($path = '/opt/mozjpeg/bin/cjpeg', $quality = 70)
    {
        $this->_path = $path;
        $this->_quality = $quality;
    }

    /**
     * @inheritdoc
     */
    public function getSupportedFormats()
    {
        return static::SUPPORTED_FORMATS;
    }

    /**
     * @inheritdoc
     */
    public function process($path)
    {
        //todo
        //$stripAll = $this->_stripAll ? ' -strip all' : '';
        //$cmd = "{$this->_path}{$stripAll} -clobber -preserve -quiet -o{$this->_level} {$path}";
        //$this->exec($cmd);

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