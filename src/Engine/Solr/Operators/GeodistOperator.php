<?php

namespace G4\DataMapper\Engine\Solr\Operators;

use G4\DataMapper\Common\CoordinatesValue;

class GeodistOperator implements QueryOperatorInterface
{
    private $name;

    private $value;

    public function __construct($name, CoordinatesValue $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function format()
    {
        return [
            'latitude'     => $this->value->getLatitude(),
            'longitude'    => $this->value->getLongitude(),
            'distance'     => $this->value->getDistance(),
            'spatialField' => $this->name,
            'filterQuery'  => '{!geofilt}',
            '_dist_'       => 'geodist()',
        ];
    }
}
