<?php

namespace G4\DataMapper\Engine\Solr\Operators;

use G4\DataMapper\Common\RangeValue;
use G4\DataMapper\Common\QueryConnector;
use G4\DataMapper\Common\QueryOperatorInterface;

class BetweenQueryOperator implements QueryOperatorInterface
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
        return $this->name
            . QueryConnector::COLON
            . QueryConnector::SQUARE_BRACKET_OPEN
            . ($this->value->isMinNull() ? QueryConnector::WILDCARD : $this->value->getMin())
            . QueryConnector::EMPTY_SPACE
            . QueryConnector::CONNECTOR_TO
            . QueryConnector::EMPTY_SPACE
            . ($this->value->isMaxNull() ? QueryConnector::WILDCARD : $this->value->getMax())
            . QueryConnector::SQUARE_BRACKET_CLOSE;
    }
}
