<?php

namespace G4\DataMapper\Engine\Elasticsearch;

class ElasticsearchGeodistSort
{
    private $identity;

    public function __construct(ElasticsearchIdentityInterface $identity)
    {
        $this->identity = $identity;
    }

    public function sort()
    {
        return $this->identity->hasCoordinates() ? $this->format() : [];
    }

    private function format()
    {
        return [
            '_geo_distance' => [
                'location' => [
                    'lat' => $this->identity->getCoordinates()->getLatitude(),
                    'lon' => $this->identity->getCoordinates()->getLongitude(),
                ],
                'order' => 'asc',
                'unit'  => 'km',
                'distance_type' => 'plane',
            ],
        ];
    }
}
