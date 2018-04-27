<?php

namespace G4\DataMapper\Engine\Elasticsearch\Operators;

use G4\DataMapper\Common\QueryOperatorInterface;
use G4\DataMapper\Common\RangeValue;
use G4\DataMapper\Common\QueryConnector;

class BetweenOperator implements QueryOperatorInterface
{
    private $name;

    private $value;

    public function __construct($name, RangeValue $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function format()
    {
        return [QueryConnector::RANGE => [$this->name => $this->handleRangeValues()]];
    }

    private function handleRangeValues()
    {
        $values = [];
        
        if (!$this->value->isMinNull()) {
            $values[QueryConnector::GREATER_THAN] = $this->value->getMin();
        }

        if (!$this->value->isMaxNull()) {
            $values[QueryConnector::LESS_THAN] = $this->value->getMax();
        }

        return $values;
    }
}
