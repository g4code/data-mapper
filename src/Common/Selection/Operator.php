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
    }

    public function getSymbol()
    {
        return $this->symbol;
    }
}