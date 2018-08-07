<?php

namespace LireinCore\Image;

trait Pixel
{
    /**
     * @param string $value
     * @param int $srcValue
     * @return int
     */
    protected function pxSize($value, $srcValue)
    {
        if (strpos($value, 'px') !== false) {
            $size = str_replace('px', '', $value);
        } elseif (strpos($value, '%') !== false) {
            $size = ((float)str_replace('%', '', $value)) * $srcValue / 100;
        } else {
            $size = $value;
        }

        return (int)round(abs((float)$size));
    }

    /**
     * @param string $value
     * @param int $srcValue
     * @param int $size
     * @return int
     */
    protected function pxOffset($value, $srcValue, $size)
    {
        if ($value == 'center') {
            $offset = ($srcValue - $size) / 2;
        } elseif ($value == 'left' || $value == 'top') {
            $offset = 0;
        } elseif ($value == 'right' || $value == 'bottom') {
            $offset = $srcValue - $size;
        } elseif (strpos($value, 'px') !== false) {
            $offset = str_replace('px', '', $value);
        } elseif (strpos($value, '%') !== false) {
            $offset = ((float)str_replace('%', '', $value)) * $srcValue / 100;
        } else {
            $offset = $value;
        }

        return (int)round((float)$offset);
    }

    /**
     * @param string $value
     * @param int $srcValue
     * @param int $size
     * @return int
     */
    protected function wtOffset($value, $srcValue, $size)
    {
        $offset = $this->pxOffset($value, $srcValue, $size);

        if ($offset + $size > $srcValue) {
            $offset = $srcValue - $size;
        }

        return $offset;
    }

    /**
     * @param string $color
     * @return string|array
     */
    protected function parseColor($color)
    {
        if (false === strpos('#', $color)) {
            $arr = explode(',', $color);
            $count = count($arr);
            if ($count == 3 || $count == 4) {
                $result = [
                    0 => trim($arr[0]),
                    1 => trim($arr[1]),
                    2 => trim($arr[2])
                ];
                if ($count == 4) {
                    $result[3] = trim($arr[3]);
                }

                return $result;
            }
        }

        return $color;
    }
}