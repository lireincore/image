<?php

namespace LireinCore\Image\Effects;

use LireinCore\Image\Effect;
use LireinCore\Image\Manipulator;

/**
 * Scale image
 */
final class Scale implements Effect
{
    /**
     * @var string
     */
    private $ratio;

    /**
     * @var string|null
     */
    private $filter;

    /**
     * Scale constructor.
     *
     * @param string      $ratio for example: 0.5 | 50%
     * @param string|null $filter
     */
    public function __construct($ratio, $filter = null)
    {
        $this->ratio = $ratio;
        $this->filter = $filter;
    }

    /**
     * @inheritdoc
     */
    public function apply(Manipulator $manipulator) : Effect
    {
        $ratio = $this->getRatio($this->ratio);
        $manipulator->scale($ratio/*, $this->filter*/); //todo

        return $this;
    }

    /**
     * @param string $value
     *
     * @return float
     */
    private function getRatio(string $value) : float
    {
        if (strpos($value, '%') !== false) {
            $result = ((float)str_replace('%', '', $value)) / 100;
        } else {
            $result = $value;
        }

        return (float)$result;
    }
}