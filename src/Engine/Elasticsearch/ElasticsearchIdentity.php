<?php

namespace G4\DataMapper\Engine\Elasticsearch;

use G4\DataMapper\Common\Identity;
use G4\DataMapper\Common\CoordinatesValue;
use G4\DataMapper\Common\Selection\Operator;
use G4\DataMapper\Common\Selection\Sort;
use G4\DataMapper\Common\SingleValue;

class ElasticsearchIdentity extends Identity implements ElasticsearchIdentityInterface
{

    /**
     * @var CoordinatesValue
     */
    private $coordinates;

    /**
     * @var string
     */
    private $consistentRandomKey;

    /**
     * @var array
     */
    private $rawQuery;

    /**
     * @var int
     */
    private $version;


    public function __construct($version = 2)
    {
        parent::__construct();
        $this->version = $version;
    }
    /**
     * @param $latitude
     * @param $longitude
     * @param $distance
     * @return ElasticsearchIdentity
     */
    public function geodist($longitude, $latitude, $distance = null)
    {
        if ($distance === null) {
            $distance = 1000000;
        }

        $this->coordinates = new CoordinatesValue($latitude, $longitude, $distance);

        if ($this->hasCoordinates()) {
            $this->addSorting('_geo_distance', new ElasticsearchGeodistSort('_geo_distance', Sort::ASCENDING));
        }

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

    /**
     * @param string $key
     */
    public function setConsistentRandomKey($key)
    {
        $this->consistentRandomKey = $key;
        $this->addSorting('_score', new Sort('_score', \G4\DataMapper\Common\Selection\Sort::ASCENDING));
    }

    /**
     * @return string
     */
    public function getConsistentRandomKey()
    {
        return $this->consistentRandomKey;
    }

    /**
     * @return bool
     */
    public function hasConsistentRandomKey()
    {
        return !($this->consistentRandomKey === null || $this->consistentRandomKey === '');
    }

    /**
     * @param $value
     * @return ElasticsearchIdentity
     */
    public function timeFromInMinutes($value)
    {
        $this->arrayException($value);
        $this->operator(Operator::TIME_FROM_IN_MINUTES, new SingleValue($value));
        return $this;
    }

    /**
     * @param string $value
     * @return ElasticsearchIdentity
     */
    public function likeCI($value)
    {
        $this->arrayException($value);
        $this->operator(Operator::LIKE_CI, new SingleValue($value));
        return $this;
    }

    /**
     * @param string $value
     * @return ElasticsearchIdentity
     */
    public function equalCI($value)
    {
        $this->arrayException($value);
        $this->operator(Operator::EQUAL_CI, new SingleValue($value));
        return $this;
    }

    /**
     * @return int
     */
    public function getVersion()
    {
        return $this->version;
    }
}
