<?php

namespace G4\DataMapper\Engine\Solr\Operators;

use G4\DataMapper\Common\SingleValue;
use G4\DataMapper\Common\QueryConnector;

class TimeFromInMinutesOperator implements QueryOperatorInterface
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
        return $this->name
            . QueryConnector::COLON
            . QueryConnector::SQUARE_BRACKET_OPEN
            . "NOW-{$this->value}MINUTES"
            . QueryConnector::EMPTY_SPACE
            . QueryConnector::CONNECTOR_TO
            . QueryConnector::EMPTY_SPACE
            . QueryConnector::WILDCARD
            . QueryConnector::SQUARE_BRACKET_CLOSE;
    }
}
