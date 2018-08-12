<?php

namespace LireinCore\Image;

class ImageHelper
{
    /**
     * @return array
     */
    public static function formats()
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
     * @return array
     */
    public static function supportedDrivers()
    {
        return ['gd', 'imagick', 'gmagick'];
    }

    /**
     * @return array
     */
    public static function supportedDestinationFormats()
    {
        return ['jpeg', 'png', 'gif', 'wbmp', 'xbm'];
    }

    /**
     * @param string $mime
     * @return null|string
     */
    public static function formatByMime($mime)
    {
        $formats = static::formats();

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
    public static function formatByExt($ext)
    {
        $formats = static::formats();

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
    public static function extensionByFormat($format)
    {
        $formats = static::formats();

        if (isset($formats[$format])) {
            return $formats[$format]['ext'][0];
        }

        return null;
    }

    /**
     * @param string $path
     * @return bool
     */
    public static function rrmdir($path)
    {
        if (($dir = @opendir($path)) === false) {
            return false;
        }

        while (($file = readdir($dir)) !== false) {
            if (($file != '.') && ($file != '..')) {
                $full = $path . DIRECTORY_SEPARATOR . $file;
                if (is_dir($full)) {
                    static::rrmdir($full);
                } else {
                    @unlink($full);
                }
            }
        }

        closedir($dir);

        return @rmdir($path);
    }

    /**
     * @param string $path
     * @param int $mode
     * @throws \RuntimeException
     */
    public static function rmkdir($path, $mode = 0775)
    {
        $path = trim($path, '\\/');
        $dirs = explode(DIRECTORY_SEPARATOR, $path);
        $pathPart = '';
        $umask = umask();
        $sysMode = $mode & ~$umask;
        $chmodFlag = $mode === $sysMode ? false : true;

        foreach ($dirs as $dir) {
            $pathPart .= DIRECTORY_SEPARATOR . $dir;
            if (!is_dir($pathPart)) {
                if (!@mkdir($pathPart, $mode)) {
                    throw new \RuntimeException("Failed to make dir '{$pathPart}'");
                }

                if ($chmodFlag) {
                    chmod($pathPart, $mode);
                }
            }
        }
    }

    /**
     * @param string|null $version
     * @return bool
     */
    public static function isGDAvailable($version = null)
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
    public static function isImagickAvailable($version = null)
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
    public static function isGmagickAvailable($version = null)
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