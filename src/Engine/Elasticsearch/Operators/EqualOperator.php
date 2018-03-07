<?php

namespace G4\DataMapper\Engine\Elasticsearch\Operators;

use G4\DataMapper\Common\QueryOperatorInterface;
use G4\DataMapper\Common\SingleValue;
use G4\DataMapper\Common\QueryConnector;

class EqualOperator implements QueryOperatorInterface
{
    public $name;

    public $value;

    public function __construct($name, SingleValue $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function format()
    {
        return [QueryConnector::MATCH => [$this->name => $this->value->getValue()]];
    }
}
