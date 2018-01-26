<?php

namespace G4\DataMapper\Engine\Solr;

use G4\DataMapper\Common\CoordinatesValue;
use G4\DataMapper\Common\QueryConnector;

class SolrGeolocationFormatter
{
    private $coordinates;

    public function __construct(CoordinatesValue $coordinates)
    {
        $this->coordinates = $coordinates;
    }

    public function hasCoordinatesSet()
    {
        return $this->coordinates instanceof CoordinatesValue;
    }

    public function format()
    {
        return $this->hasCoordinatesSet() ? $this->formattedCoordinatesArray() : $this->emptyArray();
    }

    private function emptyArray()
    {
        return [];
    }

    private function formattedCoordinatesArray()
    {
        return [
            'fq'     => '{!geofilt}',
            'sfield' => 'location',
            'pt'     => $this->coordinates->getLongitude() . QueryConnector::COMMA . $this->coordinates->getLatitude(),
            'd'      => $this->coordinates->getDistance(),
        ];
    }
}
