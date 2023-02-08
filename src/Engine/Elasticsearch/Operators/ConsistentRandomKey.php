<?php

namespace G4\DataMapper\Engine\Elasticsearch\Operators;

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
            'function_score' => [
                'query' => $this->value->getValue(),
                'random_score' => [
                    'seed' => $this->name
                ]
            ]
        ];
    }
}