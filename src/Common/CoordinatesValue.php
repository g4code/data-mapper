<?php

namespace G4\DataMapper\Common;

class CoordinatesValue implements ValueInterface
{
    private $longitude;
    private $latitude;
    private $distance;

    public function __construct($longitude, $latitude)
    {
        $this->longitude = $longitude;
        $this->latitude  = $latitude;
    }

    public function getLongitude()
    {
        return $this->longitude;
    }

    public function getLatitude()
    {
        return $this->latitude;
    }

    public function setDistance($value)
    {
        $this->distance = $value;
        return $this;
    }

    public function getDistance()
    {
        return $this->distance;
    }
}
