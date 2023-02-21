<?php

namespace G4\DataMapper\Engine\Elasticsearch\Version7\Operators;

use G4\DataMapper\Common\QueryConnector;
use G4\DataMapper\Common\QueryOperatorInterface;
use G4\DataMapper\Common\SingleValue;

class ConsistentRandomKey implements QueryOperatorInterface
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
            QueryConnector::FUNCTION_SCORE => [
                QueryConnector::NAME_QUERY_STRING_QUERY => $this->value->getValue(),
                QueryConnector::RANDOM_SCORE => [
                    QueryConnector::SEED => $this->name,
                    QueryConnector::FIELD => 'id'
                ]
            ]
        ];
    }
}