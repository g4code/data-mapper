<?php

namespace G4\DataMapper\Engine\Elasticsearch;

interface ElasticsearchIdentityInterface
{
    public function geodist($latitude, $longitude, $distance);

    public function getCoordinates();

    public function hasCoordinates();

    public function geodistMin($latitude, $longitude, $distance);

    public function getCoordinatesMin();

    public function hasCoordinatesMin();

    public function geodistMax($latitude, $longitude, $distance);

    public function getCoordinatesMax();

    public function hasCoordinatesMax();

    public function setRawQuery($value);

    public function setRawQueryWrapped($value);

    public function getRawQuery();

    public function hasRawQuery();

    /**
     * @param string $key
     */
    public function setConsistentRandomKey($key);

    /**
     * @return string
     */
    public function getConsistentRandomKey();

    /**
     * @return bool
     */
    public function hasConsistentRandomKey();

    /**
     * @param string $value
     * @return ElasticsearchIdentity
     */
    public function likeCI($value);

    public function getVersion();
}
