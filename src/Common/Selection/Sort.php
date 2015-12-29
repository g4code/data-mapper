<?php

namespace G4\DataMapper\Common\Selection;

use G4\DataMapper\Common\SortingFormatterInterface;

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
        $this->isValid();
    }

    public function getSort(SortingFormatterInterface $sortFormatter)
    {
        return $sortFormatter->format($this->name, $this->order);
    }

    private function isValid()
    {
        $validSymbols = [
            self::ASCENDING,
            self::DESCENDING,
        ];

        if (!in_array($this->order, $validSymbols)) {
            throw new \Exception('Sort order is not valid', 101);
        }
    }
}