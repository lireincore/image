<?php

namespace LireinCore\Image\PostProcessors;

use LireinCore\Image\PostProcessorInterface;

/**
 * Imagick image postprocessor
 */
class Convert implements PostProcessorInterface
{
    const SUPPORTED_FORMATS = ['jpeg'];

    /** @var string */
    protected $path;
    /** @var int */
    protected $quality;
    /** @var bool */
    protected $strip;
    /** @var bool */
    protected $interlace;

    /**
     * JpegOptim constructor.
     *
     * @param string $path path to postprocessor binary (default: '/usr/bin/convert')
     * @param int    $quality for example: 0-100, 0 - worst | 100 - best (default: 85)
     * @param bool   $strip remove all metadata (Comments, Exif, IPTC, ICC, XMP) (default: true)
     * @param bool   $interlace convert to interlace jpeg (default: true)
     */
    public function __construct($path = '/usr/bin/convert', $quality = 85, $strip = true, $interlace = true)
    {
        $this->path = $path;
        $this->quality = $quality;
        $this->strip = $strip;
        $this->interlace = $interlace;
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
        $strip = $this->strip ? '-strip' : '';
        $interlace = $this->interlace ? '-interlace' : '';
        $cmd = sprintf(
            '%s %s -sampling-factor 4:2:0 %s -quality %d %s JPEG -colorspace RGB %2$s',
            $this->path,
            $path,
            $strip,
            $this->quality,
            $interlace
        );
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
