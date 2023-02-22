<?php

namespace G4\DataMapper\Engine\Elasticsearch;

interface ElasticsearchIdentityInterface
{
    public function geodist($latitude, $longitude, $distanceMax, $distanceMin);

    public function getCoordinates();

    public function hasCoordinates();

    public function getCoordinatesMin();

    public function hasCoordinatesMin();

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

    public function equalCI($value);

    public function getVersion();

    public function queryString($value);
}
