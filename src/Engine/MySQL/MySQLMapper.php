<?php

namespace G4\DataMapper\Engine\MySQL;

use G4\DataMapper\Common\AdapterInterface;
use G4\DataMapper\Common\Bulk;
use G4\DataMapper\Common\MapperInterface;
use G4\DataMapper\Common\MappingInterface;
use G4\DataMapper\Common\IdentityInterface;
use G4\DataMapper\Common\RawData;

class MySQLMapper implements MapperInterface
{

    /**
     * @var MySQLAdapter
     */
    private $adapter;

    /**
     * @var string
     */
    private $table;

    /**
     * MySQLMapper constructor.
     * @param AdapterInterface $adapter
     * @param $table
     */
    public function __construct(AdapterInterface $adapter, $table)
    {
        $this->adapter = $adapter;
        $this->table   = $table;
    }

    /**
     * @param IdentityInterface $identity
     * @throws \Exception
     */
    public function delete(IdentityInterface $identity)
    {
        try {
            $this->adapter->delete($this->table, $this->makeSelectionFactory($identity));
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
            $rawData = $this->adapter->select($this->table, $this->makeSelectionFactory($identity));
        } catch (\Exception $exception) {
            $this->handleException($exception);
        }
        return $rawData;
    }

    /**
     * @param MappingInterface $mappings
     * @throws \Exception
     */
    public function insert(MappingInterface $mappings)
    {
        try {
            $this->adapter->insert($this->table, $mappings);
        } catch (\Exception $exception) {
            $this->handleException($exception);
        }
    }

    /**
     * @param MappingInterface $mapping
     * @param IdentityInterface $identity
     * @throws \Exception
     */
    public function update(MappingInterface $mapping, IdentityInterface $identity)
    {
        try {
            $this->adapter->update($this->table, $mapping, $this->makeSelectionFactory($identity));
        } catch (\Exception $exception) {
            $this->handleException($exception);
        }
    }

    /**
     * @param MappingInterface $mapping
     * @throws \Exception
     */
    public function upsert(MappingInterface $mapping)
    {
        try {
            $this->adapter->upsert($this->table, $mapping);
        } catch (\Exception $exception) {
            $this->handleException($exception);
        }
    }

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
     * @param \Exception $exception
     * @throws \Exception
     */
    private function handleException(\Exception $exception)
    {
        throw new \Exception($exception->getCode() . ': ' . $exception->getMessage(), 101);
    }

    /**
     * @param IdentityInterface $identity
     * @return MySQLSelectionFactory
     */
    private function makeSelectionFactory(IdentityInterface $identity)
    {
        return new MySQLSelectionFactory($identity);
    }
}