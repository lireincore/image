<?php

namespace LireinCore\Image\Effects;

use LireinCore\Image\Effect;
use LireinCore\Image\Manipulator;

/**
 * Scale image
 */
class Scale implements Effect
{
    /**
     * @var string
     */
    protected $ratio;

    /**
     * @var string
     */
    protected $filter;

    /**
     * Scale constructor.
     *
     * @param string $ratio for example: 0.5 | 50%
     * @param string $filter
     */
    public function __construct($ratio, $filter = null)
    {
        $this->ratio = $ratio;
        $this->filter = $filter;
    }

    /**
     * @inheritdoc
     */
    public function apply(Manipulator $manipulator)
    {
        $ratio = $this->getRatio($this->ratio);
        $manipulator->scale($ratio/*, $this->filter*/);

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