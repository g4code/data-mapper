<?php

namespace G4\DataMapper\Engine\Solr;

use G4\DataMapper\Common\ComparisonFormatterInterface;
use G4\DataMapper\Common\Selection\Operator;

class SolrComparisonFormatter implements ComparisonFormatterInterface
{
    const ROUND_BRACKET_OPEN   = '(';
    const ROUND_BRACKET_CLOSE  = ')';

    const SQUARE_BRACKET_CLOSE = ']';
    const SQUARE_BRACKET_OPEN  = '[';

    const CURLY_BRACKET_CLOSE  = '}';
    const CURLY_BRACKET_OPEN   = '{';

    const COLON                = ':';
    const WILDCARD             = '*';

    const CONNECTOR_TO         = 'TO';
    const CONNECTOR_OR         = 'OR';

    const EMPTY_SPACE          = ' ';
    const COMMA                = ',';


    public function format($name, Operator $operator, $value)
    {
        if($operator->getSymbol() === Operator::EQUAL) {
            return $this->formatEqualQuery($name, $value);
        } elseif ($operator->getSymbol() === Operator::GRATER_THAN) {
            return $this->formatGreaterThanQuery($name, $value);
        } elseif ($operator->getSymbol() === Operator::LESS_THAN) {
            return $this->formatLessThanQuery($name, $value);
        } elseif ($operator->getSymbol() === Operator::GRATER_THAN_OR_EQUAL) {
            return $this->formatGreaterThanOrEqualQuery($name, $value);
        } elseif ($operator->getSymbol() === Operator::LESS_THAN_OR_EQUAL) {
            return $this->formatLessThanOrEqualQuery($name, $value);
        } elseif ($operator->getSymbol() === Operator::IN) {
            return $this->formatInQuery($name, $value);
        }
    }

    private function formatEqualQuery($name, $value)
    {
        return $name
            . self::COLON
            . $value;
    }

    private function formatGreaterThanQuery($name, $value)
    {
        return $name
            . self::COLON
            . self::CURLY_BRACKET_OPEN
            . $value
            . self::EMPTY_SPACE
            . self::CONNECTOR_TO
            . self::EMPTY_SPACE
            . self::WILDCARD
            . self::CURLY_BRACKET_CLOSE;
    }

    private function formatLessThanQuery($name, $value)
    {
        return $name
            . self::COLON
            . self::CURLY_BRACKET_OPEN
            . self::WILDCARD
            . self::EMPTY_SPACE
            . self::CONNECTOR_TO
            . self::EMPTY_SPACE
            . $value
            . self::CURLY_BRACKET_CLOSE;
    }

    private function formatGreaterThanOrEqualQuery($name, $value)
    {
        return $name
            . self::COLON
            . self::SQUARE_BRACKET_OPEN
            . $value
            . self::EMPTY_SPACE
            . self::CONNECTOR_TO
            . self::EMPTY_SPACE
            . self::WILDCARD
            . self::SQUARE_BRACKET_CLOSE;
    }

    private function formatLessThanOrEqualQuery($name, $value)
    {
        return $name
            . self::COLON
            . self::SQUARE_BRACKET_OPEN
            . self::WILDCARD
            . self::EMPTY_SPACE
            . self::CONNECTOR_TO
            . self::EMPTY_SPACE
            . $value
            . self::SQUARE_BRACKET_CLOSE;
    }

    private function formatInQuery($name, $value)
    {
        return $name
            . self::COLON
            . self::ROUND_BRACKET_OPEN
            . str_replace(self::COMMA, self::EMPTY_SPACE . self::CONNECTOR_OR, $value)
            . self::ROUND_BRACKET_CLOSE;
    }
}
