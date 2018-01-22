<?php

namespace G4\DataMapper\Engine\Solr\Operators;

use G4\DataMapper\Common\RangeValue;
use G4\DataMapper\Common\QueryConnector;

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
            . QueryConnector::CURLY_BRACKET_OPEN
            . $this->value->getMin()
            . QueryConnector::EMPTY_SPACE
            . QueryConnector::CONNECTOR_TO
            . QueryConnector::EMPTY_SPACE
            . $this->value->getMax()
            . QueryConnector::CURLY_BRACKET_CLOSE;
    }
}
