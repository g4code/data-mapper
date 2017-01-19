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


    /**
     * ElasticsearchMapper constructor.
     * @param AdapterInterface $adapter
     * @param ElasticsearchIndexName $index
     * @param ElasticsearchTypeName $type
     */
    public function __construct(AdapterInterface $adapter, ElasticsearchIndexName $index, ElasticsearchTypeName $type)
    {
        $this->adapter  = $adapter;
        $this->index    = $index;
        $this->type     = $type;
    }

    /**
     * @param IdentityInterface $identity
     */
    public function delete(IdentityInterface $identity)
    {
        try {
        } catch (\Exception $exception) {
        }
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

    /**
     * @return ElasticsearchCollectionName
     */
    private function makeCollectionName()
    {
        return new ElasticsearchCollectionName($this->index, $this->type);
    }

    private function makeSelectionFactory(IdentityInterface $identity)
    {
        
    }

    /**
     * @param \Exception $exception
     * @throws \Exception
     */
    private function handleException(\Exception $exception)
    {
        throw new \Exception($exception->getCode() . ': ' . $exception->getMessage(), 101);
    }

}