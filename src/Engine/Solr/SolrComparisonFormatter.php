<?php

namespace G4\DataMapper\Engine\Solr;

use G4\DataMapper\Common\ComparisonFormatterInterface;
use G4\DataMapper\Common\RangeValue;
use G4\DataMapper\Common\Selection\Operator;
use PHP_CodeSniffer\Standards\Generic\Sniffs\Files\OneClassPerFileSniff;

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

        switch ($operator->getSymbol()) {
            case Operator::EQUAL:
                $query = $this->formatEqualQuery($name, $value);
                break;
            case Operator::GRATER_THAN:
                $query = $this->formatGreaterThanQuery($name, $value);
                break;
            case Operator::LESS_THAN:
                $query = $this->formatLessThanQuery($name, $value);
                break;
            case Operator::GRATER_THAN_OR_EQUAL:
                $query = $this->formatGreaterThanOrEqualQuery($name, $value);
                break;
            case Operator::LESS_THAN_OR_EQUAL:
                $query = $this->formatLessThanOrEqualQuery($name, $value);
                break;
            case Operator::IN:
                $query = $this->formatInQuery($name, $value);
                break;
            case Operator::LIKE:
                $query = $this->formatLikeQuery($name, $value);
                break;
        }

        return $query;
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

    private function formatLikeQuery($name, $value)
    {
        return $name
            . self::COLON
            . self::WILDCARD
            . str_replace(' ', '*', $value)
            . self::WILDCARD;
    }
}
