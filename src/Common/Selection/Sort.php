<?php

namespace G4\DataMapper\Common\Selection;

use G4\DataMapper\Common\SortingFormatterInterface;
use G4\DataMapper\Exception\InvalidValueException;
use G4\DataMapper\Exception\InvalidValueTypeException;

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
            throw new InvalidValueException('Sort order is not valid');
        }
    }
}
