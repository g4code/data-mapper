<?php

namespace G4\DataMapper\Engine\Elasticsearch;

use G4\DataMapper\Common\CoordinatesValue;

class ElasticsearchGeodistFormatter
{
    private $identity;

    public function __construct(ElasticsearchIdentityInterface $identity)
    {
        $this->identity = $identity;
    }

    public function format()
    {
        return $this->identity->hasCoordinates() ? $this->formatData($this->identity->getCoordinates()) : [];
    }

    public function formatMin()
    {
        return $this->identity->hasCoordinatesMin() ? $this->formatData($this->identity->getCoordinatesMin()) : [];
    }

    /**
     * @param CoordinatesValue $coordinates
     * @return array[]
     *
     */
    private function formatData($coordinates)
    {
        return [
            'geo_distance' => [
                'distance'     => $coordinates->getDistance() . 'km',
                'location' => [
                    'lon' => $coordinates->getLongitude(),
                    'lat' => $coordinates->getLatitude(),
                ],
            ],
        ];
    }
}
