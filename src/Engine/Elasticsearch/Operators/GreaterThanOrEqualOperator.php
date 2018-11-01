<?php

namespace G4\DataMapper\Engine\Elasticsearch\Operators;

use G4\DataMapper\Common\QueryOperatorInterface;
use G4\DataMapper\Common\QueryConnector;
use G4\DataMapper\Common\SingleValue;

class GreaterThanOrEqualOperator implements QueryOperatorInterface
{
    private $name;

    private $value;

    public function __construct($name, SingleValue $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function format()
    {
        return [
            QueryConnector::RANGE => [
                $this->name => [
                    QueryConnector::GREATER_THAN_OR_EQUAL => (string) $this->value
                ]
            ]
        ];
    }
}
