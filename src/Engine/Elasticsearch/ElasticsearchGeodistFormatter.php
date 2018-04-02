<?php

namespace G4\DataMapper\Engine\Elasticsearch;

class ElasticsearchGeodistFormatter
{
    private $identity;

    public function __construct(ElasticsearchIdentityInterface $identity)
    {
        $this->identity = $identity;
    }

    public function format()
    {
        return $this->identity->hasCoordinates() ? $this->formatData() : [];
    }

    private function formatData()
    {
        return [
            'geo_distance' => [
                'distance'     => $this->identity->getCoordinates()->getDistance() . 'km',
                'location' => [
                    'lon' => $this->identity->getCoordinates()->getLongitude(),
                    'lat' => $this->identity->getCoordinates()->getLatitude(),
                ],
            ],
        ];
    }
}
