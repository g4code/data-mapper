<?php

namespace G4\DataMapper\Common;

class RangeValue implements ValueInterface
{
    /**
     * @var string
     */
    private $min;

    /**
     * @var string
     */
    private $max;

    public function __construct($min, $max)
    {
        $this->min = $min;
        $this->max = $max;
    }

    public function getMin()
    {
        return min(array_filter([$this->min, $this->max]));
    }

    public function getMax()
    {
        return max(array_filter([$this->min, $this->max]));
    }

    public function isEmpty()
    {
        return $this->min === null && $this->max === null;
    }

    public function isMinNull()
    {
        return $this->min === null;
    }

    public function isMaxNull()
    {
        return $this->max === null;
    }
}
