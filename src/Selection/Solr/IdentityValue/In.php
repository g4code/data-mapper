<?php

namespace G4\DataMapper\Selection\Solr\IdentityValue;

class In implements \G4\DataMapper\Selection\Solr\IdentityValue\IdentityValueInterface
{
    private $value;

    public function __construct(array $value = null)
    {
        $this->value = $value;
    }

    public function hasValue()
    {
        return !empty($this->value);
    }

    public function value()
    {
        return $this->hasValue() ? $this->getJoined() : null;
    }

    private function getJoined()
    {
        return "(" . join(' ' . \G4\DataMapper\Selection\Solr\Consts\Query::CONNECTOR_OR . ' ', $this->value) . ")";
    }
}
