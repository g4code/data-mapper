<?php

namespace G4\DataMapper\Mapper;

class Elasticsearch
{

    /**
     * @var unknown
     */
    private $adapter;

    /**
     * @param \G4\DataMapper\Adapter\Elasticsearch\Client $adapter
     */
    public function __construct(\G4\DataMapper\Adapter\Elasticsearch\Client $adapter)
    {
        $this->adapter = $adapter;
    }


    public function update(\G4\DataMapper\Domain\DomainAbstract $domain)
    {
        return $this->adapter->index($domain->getRawData(), $domain->getId());
    }

}