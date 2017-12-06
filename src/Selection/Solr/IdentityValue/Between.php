<?php

namespace G4\DataMapper\Selection\Solr\IdentityValue;

class Between implements \G4\DataMapper\Selection\Solr\IdentityValue\IdentityValueInterface
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

    public function value()
    {
        return $this->hasValue() ? [$this->getMin(), $this->getMax()] : null;
    }

    private function getMax()
    {
        return $this->isMinLower() ? $this->max : $this->min;
    }

    private function getMin()
    {
        return $this->isMinLower() ? $this->min : $this->max;
    }

    private function isMinLower()
    {
        return $this->min < $this->max;
    }
}
