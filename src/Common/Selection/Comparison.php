<?php

namespace G4\DataMapper\Common\Selection;

use G4\DataMapper\Common\Quote;
use G4\DataMapper\Common\ComparisonInterface;

class Comparison implements ComparisonInterface
{

    private $name;

    private $operator;

    private $value;

    public function __construct($name, $operator, $value)
    {
        $this->name     = $name;
        $this->operator = $operator;
        $this->value    = $value;
    }

    public function getComparison()
    {
        return sprintf("%s %s %s",
            $this->name,
            $this->operator,
            (string) $this->getQuotedValue()
        );
    }

    public function getQuotedValue()
    {
        return new Quote($this->value);
    }
}