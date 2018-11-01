<?php

namespace G4\DataMapper\Engine\Solr;

use G4\DataMapper\Common\AdapterInterface;
use G4\DataMapper\Common\IdentityInterface;
use G4\DataMapper\Common\MapperInterface;
use G4\DataMapper\Common\MappingInterface;
use G4\DataMapper\Common\RawData;
use G4\DataMapper\Exception\SolrMapperException;

class SolrMapper implements MapperInterface
{
    const METHOD_SET    = 'set';
    const ID_IDENTIFIER = 'id';

    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * @var SolrCollectionName
     */
    private $collectionName;

    /**
     * @var array
     */
    private $dataForBulkUpdate;

    public function __construct(SolrCollectionName $collectionName, AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
        $this->collectionName = $collectionName;
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
     * @param IdentityInterface $identity
     * @return RawData
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
     * @return array
     */
    public function getDataForBulkUpdate()
    {
        return $this->dataForBulkUpdate;
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

    public function updateBulk()
    {
        try {
            $this->adapter->updateBulk($this->collectionName, $this->getDataForBulkUpdate());
            $this->clearData();
        } catch (\Exception $exception) {
            $this->handleException($exception);
        }
    }

    public function markForSet(MappingInterface $mapping)
    {
        $this->dataForBulkUpdate[] = $this->addToData($mapping->map(), self::METHOD_SET);
        return $this;
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

    /**
     * @param array $data
     * @param $method
     * @return array
     */
    private function addToData(array $data, $method)
    {
        $formattedData = [];

        foreach ($data as $key => $value) {
            $key === self::ID_IDENTIFIER
                ? $formattedData[$key] = $value
                : $formattedData[$key] = [$method => $value];
        }

        return $formattedData;
    }

    private function handleException(\Exception $exception)
    {
        throw new SolrMapperException($exception->getCode() . ': ' . $exception->getMessage());
    }

    /**
     * @param IdentityInterface $identity
     * @return SolrSelectionFactory
     */
    private function makeSelectionFactory(IdentityInterface $identity)
    {
        return new SolrSelectionFactory($identity);
    }

    private function clearData()
    {
        $this->dataForBulkUpdate = [];
    }
}
