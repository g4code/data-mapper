<?php

namespace G4\DataMapper\Selection\Solr\IdentityValue;

class BetweenDates implements \G4\DataMapper\Selection\Solr\IdentityValue\IdentityValueInterface
{

    private $max;

    private $min;


    public function __construct($min, $max)
    {
        $this->min = $min;
        $this->max = $max;
    }

    public function hasValue()
    {
        return $this->max !== null && $this->min !== null;
    }

    //TODO: Drasko: This needs refactoring!
    public function value()
    {
        $value = null;
        if ($this->min !== null || $this->max !== null) {
            $value       = [($this->min === null ? '*' : $this->min), ($this->max === null ? '*' : $this->max)];
        }
        if ($this->hasValue()) {
            $datetimeMin = date_create($this->min);
            $datetimeMax = date_create($this->max);
            $interval    = date_diff($datetimeMax, $datetimeMin);
            $diff        = (int) $interval->format('%R%y');
            $value       = [($diff < 0 ? $this->min : $this->max), ($diff < 0 ? $this->max : $this->min)];
        }
        return $value;
    }
}