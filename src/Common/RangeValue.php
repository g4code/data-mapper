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
        return min($this->min, $this->max);
    }

    public function getMax()
    {
        return max($this->min, $this->max);
    }
}
