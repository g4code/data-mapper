<?php

namespace G4\DataMapper\Common\Selection;

use G4\DataMapper\Common\ComparisonFormatterInterface;
use G4\DataMapper\Common\Selection\Operator;

class Comparison
{

    /**
     * @var string
     */
    private $name;

    /**
     * @var Operator
     */
    private $operator;

    private $value;


    public function __construct($name, Operator $operator, $value)
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