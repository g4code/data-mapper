<?php

namespace G4\DataMapper\Common\Selection;

class Operator
{

    const EQUAL                = 'EQUAL';
    const GRATER_THAN          = 'GRATER_THAN';
    const GRATER_THAN_OR_EQUAL = 'GRATER_THAN_OR_EQUAL';
    const IN                   = 'IN';
    const LIKE                 = 'LIKE';
    const LESS_THAN            = 'LESS_THAN';
    const LESS_THAN_OR_EQUAL   = 'LESS_THAN_OR_EQUAL';
    const NOT_EQUAL            = 'NOT_EQUAL';
    const NOT_IN               = 'NOT_IN';


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
            self::GRATER_THAN,
            self::GRATER_THAN_OR_EQUAL,
            self::IN,
            self::LIKE,
            self::LESS_THAN,
            self::LESS_THAN_OR_EQUAL,
            self::NOT_EQUAL,
            self::NOT_IN,
        ];

        if (!in_array($this->symbol, $validSymbols)) {
            throw new \Exception('Symbol is not valid', 101);
        }
    }
}