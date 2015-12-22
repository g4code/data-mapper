<?php

namespace G4\DataMapper\Common\Selection;

use G4\DataMapper\Common\ComparisonFormatterInterface;

class Comparison
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

    public function getComparison(ComparisonFormatterInterface $comparisonFormatter)
    {
        return $comparisonFormatter->format($this->name, $this->operator, $this->value);
    }
}