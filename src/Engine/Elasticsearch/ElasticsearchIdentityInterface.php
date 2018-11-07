<?php

namespace G4\DataMapper\Engine\Elasticsearch;

interface ElasticsearchIdentityInterface
{
    public function geodist($latitude, $longitude, $distance);

    public function getCoordinates();

    public function hasCoordinates();

    public function setRawQuery($value);

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
}
