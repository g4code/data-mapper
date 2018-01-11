<?php

namespace G4\DataMapper\Engine\Elasticsearch;

use G4\DataMapper\Common\SortingFormatterInterface;
use G4\DataMapper\Common\Selection\Sort;
use G4\DataMapper\Exception\OrderNotInMapException;

class ElasticsearchSortingFormatter implements SortingFormatterInterface
{
    private $map = [
        Sort::ASCENDING  => 'asc',
        Sort::DESCENDING => 'desc',
    ];

    public function format($name, $order)
    {
        return sprintf("%s:%s", $name, $this->sortMap($order));
    }

    private function sortMap($order)
    {
        if (!isset($this->map[$order])) {
            throw new OrderNotInMapException();
        }

        return $this->map[$order];
    }
}
