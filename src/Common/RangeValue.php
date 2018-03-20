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
        if($this->min === null) {
            return $this->max;
        } elseif ($this->max === null) {
            return $this->min;
        } else {
            return min([$this->min, $this->max]);
        }
    }

    public function getMax()
    {
        if($this->min === null) {
            return $this->max;
        } elseif ($this->max === null) {
            return $this->min;
        } else {
            return max([$this->min, $this->max]);
        }
    }

    public function isEmpty()
    {
        return $this->min === null && $this->max === null;
    }

    public function isMinNull()
    {
        return $this->min === null && $this->max !== 0;
    }

    public function isMaxNull()
    {
        return $this->max === null || ($this->min === null && $this->max === 0);
    }
}
