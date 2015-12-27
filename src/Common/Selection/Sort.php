<?php

namespace G4\DataMapper\Selection;

class Sort
{

    const ASCENDING  = 'ASC';
    const DESCENDING = 'DESC';

    private $name;

    private $order;


    public function __construct($name, $order)
    {
        $this->name  = $name;
        $this->order = $order;
    }


}