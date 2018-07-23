<?php

namespace LireinCore\Image;

class ImageHelper
{
    /**
     * @return array
     */
    public static function getFormats()
    {
        return [
            'jpeg' => [
                'mime' => ['image/jpeg', 'image/pjpeg'],
                'ext' => ['jpg', 'jpeg', 'jpe', 'pjpeg'],
            ],
            'png' => [
                'mime' => ['image/png', 'image/x-png'],
                'ext' => ['png'],
            ],
            'gif' => [
                'mime' => ['image/gif'],
                'ext' => ['gif'],
            ],
            'wbmp' => [
                'mime' => ['image/vnd.wap.wbmp'],
                'ext' => ['wbmp'],
            ],
            'xbm' => [
                'mime' => ['image/xbm', 'image/x-xbitmap'],
                'ext' => ['xbm'],
            ],
            'bmp' => [
                'mime' => ['image/bmp', 'image/x-windows-bmp', 'image/x-ms-bmp'],
                'ext' => ['bmp'],
            ],
            'ico' => [
                'mime' => ['image/x-icon', 'image/vnd.microsoft.icon', 'image/x-ico'],
                'ext' => ['ico'],
            ],
            'webp' => [
                'mime' => ['image/webp'],
                'ext' => ['webp'],
            ],
            'tiff' => [
                'mime' => ['image/tiff'],
                'ext' => ['tif', 'tiff'],
            ],
            'svg' => [
                'mime' => ['image/svg+xml'],
                'ext' => ['svg'/*, 'svgz'*/],
            ],
            'psd' => [
                'mime' => ['image/psd'],
                'ext' => ['psd'],
            ],
            'aiff' => [
                'mime' => ['image/iff'],
                'ext' => ['aiff'],
            ],
            'swf' => [
                'mime' => ['application/x-shockwave-flash'],
                'ext' => ['swf'],
            ],
            'swc' => [
                'mime' => ['application/x-shockwave-flash'],
                'ext' => ['swc'],
            ],
            'jp2' => [
                'mime' => ['image/jp2'],
                'ext' => ['jp2'],
            ],
            'jb2' => [
                'mime' => ['application/octet-stream'],
                'ext' => ['jb2'],
            ],
            'jpc' => [
                'mime' => ['application/octet-stream'],
                'ext' => ['jpc'],
            ],
            'jpf' => [
                'mime' => ['application/octet-stream'],
                'ext' => ['jpf'],
            ]
        ];
    }

    /**
     * @param string $mime
     * @return null|string
     */
    public static function getFormatByMime($mime)
    {
        $formats = static::getFormats();

        foreach ($formats as $name => $format) {
            if (in_array($mime, $format['mime'])) {
                return $name;
            }
        }

        return null;
    }

    /**
     * @param string $ext
     * @return null|string
     */
    public static function getFormatByExt($ext)
    {
        $formats = static::getFormats();

        foreach ($formats as $name => $format) {
            if (in_array($ext, $format['ext'])) {
                return $name;
            }
        }

        return null;
    }

    /**
     * @param string $format
     * @return null|string
     */
    public static function getExtByFormat($format)
    {
        $formats = static::getFormats();

        if (isset($formats[$format])) {
            return $formats[$format]['ext'][0];
        }

        return null;
    }

    /**
     * @param string $pathname
     * @return bool
     */
    public static function rrmdir($pathname)
    {
        if (($dir = @opendir($pathname)) === false) {
            return false;
        }

        while (($file = readdir($dir)) !== false) {
            if (($file != '.') && ($file != '..')) {
                $full = $pathname . DIRECTORY_SEPARATOR . $file;
                if (is_dir($full)) {
                    static::rrmdir($full);
                } else {
                    @unlink($full);
                }
            }
        }

        closedir($dir);

        return @rmdir($pathname);
    }

    /**
     * @param string $pathname
     * @param int $mode
     * @throws \RuntimeException
     */
    public static function rmkdir($pathname, $mode = 0775)
    {
        $pathname = trim($pathname, '\\/');
        $dirs = explode(DIRECTORY_SEPARATOR, $pathname);
        $path = '';
        $umask = umask();
        $sysMode = $mode & ~$umask;
        $chmodFlag = $mode === $sysMode ? false : true;

        foreach ($dirs as $dir) {
            $path .= DIRECTORY_SEPARATOR . $dir;
            if (!is_dir($path)) {
                if (!@mkdir($path, $mode)) {
                    throw new \RuntimeException("Failed to make dir '{$path}'");
                }

                if ($chmodFlag) {
                    chmod($path, $mode);
                }
            }
        }
    }

    /**
     * @param string|null $version
     * @return bool
     */
    public static function checkIsGDAvailable($version = null)
    {
        if (function_exists('gd_info')) {
            if ($version !== null) {
                if (version_compare($version, GD_VERSION) > 0) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }

    /**
     * @param string|null $version
     * @return bool
     */
    public static function checkIsImagickAvailable($version = null)
    {
        if (class_exists('Imagick')) {
            if ($version !== null) {
                $versionData = \Imagick::getVersion();
                preg_match('/ImageMagick ([0-9]+\.[0-9]+\.[0-9]+)/', $versionData['versionString'], $versionParts);
                if (version_compare($version, $versionParts[1]) > 0) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }

    /**
     * @param string|null $version
     * @return bool
     */
    public static function checkIsGmagickAvailable($version = null)
    {
        if (class_exists('Gmagick')) {
            if ($version !== null) {
                try {
                    $versionData = (new \Gmagick())->getVersion();
                } catch (\GmagickException $ex) {
                    return false;
                }
                preg_match('/GraphicsMagick ([0-9]+\.[0-9]+\.[0-9]+)/', $versionData['versionString'], $versionParts);
                if (version_compare($version, $versionParts[1]) > 0) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }
}