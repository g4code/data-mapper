<?php

namespace G4\DataMapper\Engine\MySQL;

use G4\DataMapper\Common\SortingFormatterInterface;
use G4\DataMapper\Common\Selection\Sort;
use G4\DataMapper\Exception\InvalidValueException;

class MySQLSortingFormatter implements SortingFormatterInterface
{

    private $map = [
        Sort::ASCENDING => 'ASC',
        Sort::DESCENDING => 'DESC',
    ];

    public function format($name, $order)
    {
        return sprintf(
            "%s %s",
            $name,
            $this->sortMap($order)
        );
    }

    private function sortMap($order)
    {
        if (!isset($this->map[$order])) {
            throw new InvalidValueException('Order not in map');
        }

        return $this->map[$order];
    }
}
