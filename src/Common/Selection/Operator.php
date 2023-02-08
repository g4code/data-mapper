<?php

namespace G4\DataMapper\Common\Selection;

use G4\DataMapper\Exception\InvalidValueException;
use G4\DataMapper\Exception\InvalidValueTypeException;

class Operator
{

    const EQUAL                = 'EQUAL';
    const EQUAL_CI             = 'EQUAL_CI';
    const GRATER_THAN          = 'GRATER_THAN';
    const GRATER_THAN_OR_EQUAL = 'GRATER_THAN_OR_EQUAL';
    const IN                   = 'IN';
    const LIKE                 = 'LIKE';
    const LIKE_CI              = 'LIKE_CI';
    const LESS_THAN            = 'LESS_THAN';
    const LESS_THAN_OR_EQUAL   = 'LESS_THAN_OR_EQUAL';
    const NOT_EQUAL            = 'NOT_EQUAL';
    const NOT_IN               = 'NOT_IN';
    const BETWEEN              = 'BETWEEN';
    const TIME_FROM_IN_MINUTES = 'TIME_FROM_IN_MINUTES';
    const GEODIST              = 'GEODIST';
    const MISSING              = 'MISSING';
    const EXISTS               = 'EXISTS';
    const QUERY_STRING         = 'QUERY_STRING';
    const CONSISTENT_RANDOM_KEY = 'CONSISTENT_RANDOM_KEY';


    /**
     * @var string
     */
    private $symbol;

    public function __construct($symbol)
    {
        $this->symbol = $symbol;
        $this->isValid();
    }

    public function getSymbol()
    {
        return $this->symbol;
    }

    private function isValid()
    {
        $validSymbols = [
            self::EQUAL,
            self::EQUAL_CI,
            self::GRATER_THAN,
            self::GRATER_THAN_OR_EQUAL,
            self::IN,
            self::LIKE,
            self::LIKE_CI,
            self::LESS_THAN,
            self::LESS_THAN_OR_EQUAL,
            self::NOT_EQUAL,
            self::NOT_IN,
            self::BETWEEN,
            self::TIME_FROM_IN_MINUTES,
            self::GEODIST,
            self::MISSING,
            self::EXISTS,
            self::QUERY_STRING,
            self::CONSISTENT_RANDOM_KEY,
        ];

        if (!in_array($this->symbol, $validSymbols)) {
            throw new InvalidValueException('Symbol is not valid');
        }
    }
}
