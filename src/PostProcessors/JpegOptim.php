<?php

namespace LireinCore\Image\PostProcessors;

use LireinCore\Image\PostProcessorInterface;

/**
 * JpegOptim image postprocessor
 */
class JpegOptim implements PostProcessorInterface
{
    const SUPPORTED_FORMATS = ['jpeg'];

    /**
     * @var string
     */
    protected $_path;

    /**
     * @var int
     */
    protected $_quality;

    /**
     * @var bool
     */
    protected $_stripAll;

    /**
     * @var bool
     */
    protected $_progressive;

    /**
     * JpegOptim constructor.
     *
     * @param string $path path to postprocessor binary (default: '/usr/bin/jpegoptim')
     * @param int $quality for example: 0-100, 0 - worst | 100 - best (default: 85)
     * @param bool $strip_all remove all metadata (Comments, Exif, IPTC, ICC, XMP) (default: true)
     * @param bool $progressive convert to progressive jpeg (default: true)
     */
    public function __construct($path = '/usr/bin/jpegoptim', $quality = 85, $strip_all = true, $progressive = true)
    {
        $this->_path = $path;
        $this->_quality = $quality;
        $this->_stripAll = $strip_all;
        $this->_progressive = $progressive;
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
        $stripAll = $this->_stripAll ? ' --strip-all' : '';
        $progressive = $this->_progressive ? ' --all-progressive' : '';
        $destDir = ' --dest=' . dirname($path);
        $cmd = "{$this->_path}{$stripAll}{$progressive}{$destDir} -q -p -o -P -m{$this->_quality} {$path}";
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
