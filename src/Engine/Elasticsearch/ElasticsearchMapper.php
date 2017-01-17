<?php

namespace G4\DataMapper\Engine\Elasticsearch;

use G4\DataMapper\Common\AdapterInterface;
use G4\DataMapper\Common\MapperInterface;
use G4\DataMapper\Common\IdentityInterface;
use G4\DataMapper\Common\MappingInterface;


class ElasticsearchMapper implements MapperInterface
{

    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * @var ElasticsearchIndexName
     */
    private $index;

    /**
     * @var ElasticsearchTypeName
     */
    private $type;



    public function __construct(AdapterInterface $adapter, ElasticsearchIndexName $index, ElasticsearchTypeName $type)
    {
        $this->adapter  = $adapter;
        $this->index    = $index;
        $this->type     = $type;
    }


    public function delete(IdentityInterface $identity)
    {
        // TODO: Implement delete() method.
    }


    public function find(IdentityInterface $identity)
    {
        // TODO: Implement find() method.
    }


    public function insert(MappingInterface $mapping)
    {
        // TODO: Implement insert() method.
    }


    public function query($query)
    {
        // TODO: Implement query() method.
    }


    public function update(MappingInterface $mapping, IdentityInterface $identity)
    {
        // TODO: Implement update() method.
    }


    public function upsert(MappingInterface $mapping)
    {
        // TODO: Implement upsert() method.
    }


}