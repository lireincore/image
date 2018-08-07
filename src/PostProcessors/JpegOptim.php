<?php

namespace LireinCore\Image\PostProcessors;

use LireinCore\Image\PostProcessor;

/**
 * JpegOptim image postprocessor
 */
class JpegOptim implements PostProcessor
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
    protected $stripAll;

    /**
     * @var bool
     */
    protected $progressive;

    /**
     * @var bool
     */
    protected $supportedFormats = ['jpeg'];

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
        $this->path = $path;
        $this->quality = $quality;
        $this->stripAll = $strip_all;
        $this->progressive = $progressive;
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
        $stripAll = $this->stripAll ? ' --strip-all' : '';
        $progressive = $this->progressive ? ' --all-progressive' : '';
        $destDir = ' --dest=' . dirname($path);
        $cmd = "{$this->path}{$stripAll}{$progressive}{$destDir} -q -p -o -P -m{$this->quality} {$path}";
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