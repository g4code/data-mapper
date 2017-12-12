<?php

namespace G4\DataMapper\Engine\Solr;

use G4\DataMapper\Common\SortingFormatterInterface;
use G4\DataMapper\Common\Selection\Sort;

class SolrSortingFormatter implements SortingFormatterInterface
{
    private $map = [
        Sort::ASCENDING  => 'asc',
        Sort::DESCENDING => 'desc',
    ];
    
    public function format($name, $order)
    {
        return sprintf("%s %s", $name, $this->sortMap($order));
    }

    private function sortMap($order)
    {
        if(!isset($this->map[$order])) {
            throw new \Exception('Order is not in map', 101);
        }

        return $this->map[$order];
    }
}
