<?php

namespace G4\DataMapper\Common\Selection;

use G4\DataMapper\Common\SortFormatterInterface;

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

    public function getSort(SortFormatterInterface $sortFormatter)
    {
        return $sortFormatter->format($this->name, $this->order);
    }
}