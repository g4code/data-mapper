<?php

namespace G4\DataMapper\Engine\Elasticsearch\Operators;

use G4\DataMapper\Common\QueryConnector;
use G4\DataMapper\Common\QueryOperatorInterface;
use G4\DataMapper\Common\SingleValue;

class QueryStringOperator implements QueryOperatorInterface
{
    private $value;

    public function __construct($name, SingleValue $value)
    {
        $this->value = $value;
    }

    public function format()
    {
        return [
            QueryConnector::NAME_QUERY_STRING_QUERY =>
                [
                    QueryConnector::NAME_QUERY_STRING =>
                        [
                            QueryConnector::NAME_QUERY_STRING_QUERY => $this->value->getValue(),
                            QueryConnector::ANALYZE_WILDCARD => true,
                        ],
                ],
        ];
    }
}
