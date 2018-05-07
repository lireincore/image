<?php

namespace LireinCore\Image\Effects;

use LireinCore\Image\EffectInterface;
use LireinCore\Image\ImageInterface;

/**
 * Scale image
 */
class Scale implements EffectInterface
{
    /**
     * @var string
     */
    protected $_ratio;

    /**
     * @var string
     */
    protected $_filter;

    /**
     * Scale constructor.
     *
     * @param string $ratio for example: 0.5 | 50%
     * @param string $filter
     */
    public function __construct($ratio, $filter = null)
    {
        $this->_ratio = $ratio;
        $this->_filter = $filter;
    }

    /**
     * @inheritdoc
     */
    public function apply(ImageInterface $img)
    {
        $ratio = $this->getRatio($this->_ratio);
        $img->scale($ratio/*, $this->_filter*/);

        return $this;
    }

    /**
     * @param string $value
     *
     * @return float
     */
    protected function getRatio($value)
    {
        if (strpos($value, '%') !== false) {
            $result = ((float)str_replace('%', '', $value)) / 100;
        } else {
            $result = $value;
        }

        return (float)$result;
    }
}
