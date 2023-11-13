<?php

namespace G4\DataMapper\Common;

use G4\DataMapper\Common\MappingInterface;
use G4\DataMapper\Exception\BulkOperationException;

class Bulk implements \Countable
{

    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * @var \ArrayIterator
     */
    private $data;

    /**
     * @var CollectionNameInterface
     */
    private $collectionName;


    public function __construct(AdapterInterface $adapter, CollectionNameInterface $collectionName)
    {
        $this->adapter          = $adapter;
        $this->collectionName   = $collectionName;
        $this->data             = new \ArrayIterator([]);
    }

    /**
     * @param MappingInterface $mapping
     * @return Bulk
     */
    public function add(MappingInterface $mapping)
    {
        $this->data->append($mapping);
        return $this;
    }

    public function insert()
    {
        try {
            $this->adapter->insertBulk($this->collectionName, $this->getData());
        } catch (\Exception $exception) {
            throw new BulkOperationException($exception->getCode() . ': ' . $exception->getMessage());
        }
    }

    public function upsert()
    {
        try {
            $this->adapter->upsertBulk($this->collectionName, $this->getData());
        } catch (\Exception $exception) {
            throw new BulkOperationException($exception->getCode() . ': ' . $exception->getMessage());
        }
    }

    /**
     * @return \ArrayIterator
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->data);
    }
}
