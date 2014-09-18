<?php

namespace G4\DataMapper\Selection\Solr\IdentityValue;

class Like implements \G4\DataMapper\Selection\Solr\IdentityValue\IdentityValueInterface
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function hasValue()
    {
        return $this->value !== null;
    }

    public function value()
    {
        return $this->hasValue() ? $this->getJoined() : null;
    }

    private function getJoined()
    {
        return \G4\DataMapper\Selection\Solr\Consts\Query::WILDECARD . $this->value . \G4\DataMapper\Selection\Solr\Consts\Query::WILDECARD;
    }
}