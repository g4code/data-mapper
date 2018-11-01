<?php

namespace G4\DataMapper\Engine\Solr;

use G4\DataMapper\Common\Identity;
use G4\DataMapper\Common\Selection\Operator;
use G4\DataMapper\Common\SingleValue;
use G4\DataMapper\Common\CoordinatesValue;

class SolrIdentity extends Identity implements SolrIdentityInterface
{

    /**
     * @var string
     */
    private $rawQuery;

    /**
     * @var CoordinatesValue
     */
    private $coordinates;

    /**
     * @param $value
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

    /**
     * @param string $value
     * @return SolrIdentity
     */
    public function timeFromInMinutes($value)
    {
        $this->arrayException($value);
        $this->operator(Operator::TIME_FROM_IN_MINUTES, new SingleValue($value));
        return $this;
    }

    /**
     * @param $latitude
     * @param $longitude
     * @param $distance
     * @return SolrIdentity
     */
    public function geodist($latitude, $longitude, $distance = null)
    {
        if ($distance === null) {
            $distance = 1000000;
        }

        $this->coordinates = new CoordinatesValue($latitude, $longitude, $distance);

        if (!$this->coordinates->isEmpty()) {
            $this->sortAscending('geodist()');
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getCoordinates()
    {
        return ($this->coordinates === null || $this->coordinates->isEmpty()) ? [] : $this->coordinates->formatForSolr();
    }
}
