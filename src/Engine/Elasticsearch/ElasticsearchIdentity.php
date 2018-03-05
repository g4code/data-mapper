<?php

namespace G4\DataMapper\Engine\Elasticsearch;

use G4\DataMapper\Common\Identity;
use G4\DataMapper\Common\CoordinatesValue;

class ElasticsearchIdentity extends Identity
{

    /**
     * @var CoordinatesValue
     */
    private $coordinates;

    /**
     * @param $latitude
     * @param $longitude
     * @param $distance
     * @return ElasticsearchIdentity
     */
    public function geodist($latitude, $longitude, $distance = null)
    {
        if($distance === null) {
            $distance = 1000000;
        }

        $this->coordinates = new CoordinatesValue($latitude, $longitude, $distance);

        return $this;
    }

    /**
     * @return array
     */
    public function getCoordinates()
    {
        return ($this->coordinates === null || $this->coordinates->isEmpty()) ? [] : $this->coordinates->formatForElasticsearch();
    }

}
