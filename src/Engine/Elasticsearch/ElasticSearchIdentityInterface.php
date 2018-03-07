<?php

namespace G4\DataMapper\Engine\Elasticsearch;

interface ElasticSearchIdentityInterface
{
    public function geodist($latitude, $longitude, $distance);

    public function getCoordinates();

    public function hasCoordinates();

    public function setRawQuery($value);

    public function getRawQuery();

    public function hasRawQuery();
}
