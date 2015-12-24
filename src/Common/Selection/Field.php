<?php

namespace G4\DataMapper\Common\Selection;

use G4\DataMapper\Common\Selection\Comparison;
use G4\DataMapper\Common\Selection\Operator;

class Field
{

    /**
     * @var array
     */
    private $comparisons;

    /**
     * @var string
     */
    private $name;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name        = $name;
        $this->comparisons = [];
    }

    public function add(Operator $operator, $value)
    {
        $this->comparisons[] = new Comparison($this->name, $operator, $value);
    }

    /**
     * @return array
     */
    public function getComparisons()
    {
        return $this->comparisons;
    }

    public function isIncomplete()
    {
        return count($this->comparisons) === 0;
    }
}