<?php

namespace G4\DataMapper\Engine\Elasticsearch;

use G4\DataMapper\Common\Selection\Sort;
use G4\DataMapper\Common\SortingFormatterInterface;
use G4\DataMapper\Exception\OrderNotInMapException;

class ElasticsearchGeodistSortFormatter implements SortingFormatterInterface
{
    private $identity;

    private $map = [
        Sort::ASCENDING  => 'asc',
        Sort::DESCENDING => 'desc',
    ];

    /**
     * ElasticsearchGeodistSortFormatter constructor.
     * @param ElasticsearchIdentityInterface $identity
     */
    public function __construct(ElasticsearchIdentityInterface $identity)
    {
        $this->identity = $identity;
    }

    /**
     * @param string $name
     * @param string $order
     * @return array
     */
    public function format($name, $order)
    {
        return $this->identity->hasCoordinates() ? $this->formatData($name, $order) : [];
    }

    private function formatData($name, $order)
    {
        return [
            $name => [ // _geo_distance
                'location' => [
                    'lat' => $this->identity->getCoordinates()->getLatitude(),
                    'lon' => $this->identity->getCoordinates()->getLongitude(),
                ],
                'order' => $this->sortMap($order),
                'unit'  => 'km',
                'distance_type' => 'plane',
            ],
        ];
    }

    private function sortMap($order)
    {
        if (!isset($this->map[$order])) {
            throw new OrderNotInMapException();
        }

        return $this->map[$order];
    }
}
