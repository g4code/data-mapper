<?php

namespace G4\DataMapper\Engine\Solr\Operators;

use G4\DataMapper\Common\SingleValue;
use G4\DataMapper\Common\QueryConnector;
use G4\DataMapper\Common\QueryOperatorInterface;

class InOperator implements QueryOperatorInterface
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
            . QueryConnector::ROUND_BRACKET_OPEN
            . str_replace(QueryConnector::COMMA, QueryConnector::EMPTY_SPACE . QueryConnector::CONNECTOR_OR . QueryConnector::EMPTY_SPACE, $this->getValueAttributeWithoutWhitespaceCharacters())
            . QueryConnector::ROUND_BRACKET_CLOSE;
    }

    private function getValueAttributeWithoutWhitespaceCharacters()
    {
        return preg_replace('/\s+/', '', $this->value);
    }
}
