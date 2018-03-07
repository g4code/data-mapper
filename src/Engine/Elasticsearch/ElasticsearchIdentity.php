<?php

namespace G4\DataMapper\Engine\Elasticsearch;

use G4\DataMapper\Common\Identity;
use G4\DataMapper\Common\CoordinatesValue;

class ElasticsearchIdentity extends Identity implements ElasticSearchIdentityInterface
{

    /**
     * @var CoordinatesValue
     */
    private $coordinates;

    /**
     * @var array
     */
    private $rawQuery;

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
     * @return CoordinatesValue
     */
    public function getCoordinates()
    {
        return $this->coordinates;
    }

    /**
     * @return bool
     */
    public function hasCoordinates()
    {
        return !($this->coordinates === null || $this->coordinates->isEmpty());
    }

    /**
     * @param $value
     * @return ElasticsearchIdentity
     */
    public function setRawQuery($value)
    {
        $this->rawQuery = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getRawQuery()
    {
        return $this->rawQuery;
    }

    /**
     * @return bool
     */
    public function hasRawQuery()
    {
        return !empty($this->rawQuery);
    }
}
