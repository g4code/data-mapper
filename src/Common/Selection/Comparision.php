<?php

namespace G4\DataMapper\Common\Selection;

class Comparision
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



}