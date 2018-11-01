<?php

namespace G4\DataMapper\Selection\Solr\IdentityValue;

use G4\DataMapper\Selection\Solr\Consts\Time;
use G4\DataMapper\Selection\Solr\Consts\Query;

class TimeRange implements \G4\DataMapper\Selection\Solr\IdentityValue\IdentityValueInterface
{
    private $max;

    private $min;

    /**
     * @var \G4\DataMapper\Selection\IdentityField
     */
    private $field;


    public function __construct($min, $max, \G4\DataMapper\Selection\IdentityField $field)
    {
        $this->min   = $min;
        $this->max   = $max;
        $this->field = $field;
    }

    public function hasValue()
    {
        return $this->hasMax() || $this->hasMin();
    }

    public function value()
    {
        return $this->hasValue() ? [$this->getMin(), $this->getMax()] : null;
    }

    private function getMax()
    {
        return $this->hasMax()
            ? (Time::NOW . ((int) $this->max > 0 ? '+' : '') . $this->max . Time::MINUTES)
            : ($this->field->hasCurrentValueMax() ? $this->field->getCurrentValueMax() : Query::WILDCARD);
    }

    private function getMin()
    {
        return $this->hasMin()
            ? (Time::NOW . ((int) $this->min > 0 ? '+' : '') . $this->min . Time::MINUTES)
            : ($this->field->hasCurrentValueMin() ? $this->field->getCurrentValueMin() : Query::WILDCARD);
    }

    private function hasMax()
    {
        return $this->max !== null;
    }

    private function hasMin()
    {
        return $this->min !== null;
    }
}
