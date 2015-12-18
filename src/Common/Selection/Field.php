<?php

namespace G4\DataMapper\Common\Selection;

use G4\DataMapper\Common\Selection\Comparision;

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

    public function add($symbol, $value)
    {
        $this->comparisons[] = new Comparision($this->name, $symbol, $value);
    }

    public function getComparisions()
    {
        return $this->comparisons;
    }

    public function isIncomplete()
    {
        return count($this->comparisons) === 0;
    }
}