<?php

namespace G4\DataMapper\Common;

class CoordinatesValue implements ValueInterface
{
    private $longitude;
    private $latitude;
    private $distance;

    public function __construct($longitude, $latitude, $distance)
    {
        $this->longitude = $longitude;
        $this->latitude  = $latitude;
        $this->distance  = $distance;
    }

    public function getLongitude()
    {
        return $this->longitude;
    }

    public function getLatitude()
    {
        return $this->latitude;
    }

    public function getDistance()
    {
        return $this->distance;
    }
}
