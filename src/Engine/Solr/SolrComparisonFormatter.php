<?php

namespace G4\DataMapper\Engine\Solr;

use G4\DataMapper\Common\ComparisonFormatterInterface;
use G4\DataMapper\Common\Selection\Operator;

class SolrComparisonFormatter implements ComparisonFormatterInterface
{
    const BRACKET_CLOSE       = ']';
    const BRACKET_OPEN        = '[';

    const CURLY_BRACKET_CLOSE = '}';
    const CURLY_BRACKET_OPEN  = '{';

    const COLON               = ':';
    const TO                  = 'TO';
    const WILDCARD            = '*';

    const EMPTY_SPACE         = ' ';


    public function format($name, Operator $operator, $value)
    {
        if($operator->getSymbol() === Operator::EQUAL) {
            return $name . self::COLON . $value;
        } elseif ($operator->getSymbol() === Operator::GRATER_THAN) {
            return $name . self::COLON . self::CURLY_BRACKET_OPEN . $value . self::EMPTY_SPACE . self::TO . self::EMPTY_SPACE . self::WILDCARD . self::CURLY_BRACKET_CLOSE;
        } elseif ($operator->getSymbol() === Operator::LESS_THAN) {
            return $name . self::COLON . self::CURLY_BRACKET_OPEN . self::WILDCARD . self::EMPTY_SPACE . self::TO . self::EMPTY_SPACE . $value . self::CURLY_BRACKET_CLOSE;
        } elseif ($operator->getSymbol() === Operator::GRATER_THAN_OR_EQUAL) {
            return $name . self::COLON . self::BRACKET_OPEN . $value . self::EMPTY_SPACE . self::TO . self::EMPTY_SPACE . self::WILDCARD . self::BRACKET_CLOSE;
        } elseif ($operator->getSymbol() === Operator::LESS_THAN_OR_EQUAL) {
            return $name . self::COLON . self::BRACKET_OPEN . self::WILDCARD . self::EMPTY_SPACE . self::TO . self::EMPTY_SPACE . $value . self::BRACKET_CLOSE;
        }
    }
}
