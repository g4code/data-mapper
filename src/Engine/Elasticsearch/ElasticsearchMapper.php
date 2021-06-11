<?php

namespace G4\DataMapper\Engine\Elasticsearch;

use G4\DataMapper\Common\AdapterInterface;
use G4\DataMapper\Common\IdentifiableMapperInterface;
use G4\DataMapper\Common\MapperInterface;
use G4\DataMapper\Common\IdentityInterface;
use G4\DataMapper\Common\MappingInterface;
use G4\DataMapper\Exception\ElasticSearchMapperException;
use G4\DataMapper\Exception\NotImplementedException;

class ElasticsearchMapper implements MapperInterface
{

    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * @var ElasticsearchCollectionName
     */
    private $collectionName;


    /**
     * ElasticsearchMapper constructor.
     * @param ElasticsearchCollectionName $collectionName
     * @param AdapterInterface $adapter
     */
    public function __construct(ElasticsearchCollectionName $collectionName, AdapterInterface $adapter)
    {
        $this->collectionName = $collectionName;
        $this->adapter = $adapter;
    }

    /**
     * @param IdentityInterface $identity
     */
    public function delete(IdentityInterface $identity)
    {
        try {
            $this->adapter->delete($this->collectionName, $this->makeSelectionFactory($identity));
        } catch (\Exception $exception) {
            $this->handleException($exception);
        }
    }

    /**
     * @param IdentifiableMapperInterface[] ...$mappings
     */
    public function deleteBulk(IdentifiableMapperInterface ...$mappings)
    {
        try {
            $this->adapter->deleteBulk($this->collectionName, $mappings);
        } catch (\Exception $exception) {
            $this->handleException($exception);
        }
    }

    /**
     * @param IdentityInterface $identity
     */
    public function find(IdentityInterface $identity)
    {
        try {
            $rawData = $this->adapter->select($this->collectionName, $this->makeSelectionFactory($identity));
        } catch (\Exception $exception) {
            $this->handleException($exception);
        }
        return $rawData;
    }

    /**
     * @param MappingInterface $mapping
     */
    public function insert(MappingInterface $mapping)
    {
        try {
            $this->adapter->insert($this->collectionName, $mapping);
        } catch (\Exception $exception) {
            $this->handleException($exception);
        }
    }

    /**
     * @param MappingInterface $mapping
     */
    public function upsert(MappingInterface $mapping)
    {
        try {
            $this->adapter->upsert($this->collectionName, $mapping);
        } catch (\Exception $exception) {
            $this->handleException($exception);
        }
    }

    /**
     * @param MappingInterface $mapping
     * @param IdentityInterface $identity
     */
    public function update(MappingInterface $mapping, IdentityInterface $identity)
    {
        try {
            $this->adapter->update($this->collectionName, $mapping, $this->makeSelectionFactory($identity));
        } catch (\Exception $exception) {
            $this->handleException($exception);
        }
    }

    /**
     * @param IdentifiableMapperInterface[] ...$mappings
     */
    public function updateBulk(IdentifiableMapperInterface ...$mappings)
    {
        try {
            $this->adapter->updateBulk($this->collectionName, $mappings);
        } catch (\Exception $exception) {
            $this->handleException($exception);
        }
    }

    /**
     * @param mixed $query
     * @return mixed
     */
    public function query($query)
    {
        try {
            $queryResult = $this->adapter->query($query);
        } catch (\Exception $exception) {
            $this->handleException($exception);
        }
        return $queryResult;
    }

    private function handleException(\Exception $exception)
    {
        throw new ElasticSearchMapperException($exception->getCode() . ': ' . $exception->getMessage());
    }

    /**
     * @param IdentityInterface $identity
     * @return ElasticsearchSelectionFactory
     */
    private function makeSelectionFactory(IdentityInterface $identity)
    {
        return new ElasticsearchSelectionFactory($identity);
    }

    public function simpleQuery($query)
    {
        throw new NotImplementedException();
    }
}
