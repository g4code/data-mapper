<?php

namespace G4\DataMapper\Engine\MySQL;

use G4\DataMapper\Common\AdapterInterface;
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
     */
    public function delete(IdentityInterface $identity)
    {
        $this->adapter->delete($this->table, $this->makeSelectionFactory($identity));
    }

    /**
     * @param IdentityInterface $identity
     * @return RawData
     */
    public function find(IdentityInterface $identity)
    {
        return $this->adapter->select($this->table, $this->makeSelectionFactory($identity));
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
            throw new \Exception($exception->getMessage(), 101);
        }
    }

    /**
     * @param MappingInterface $mapping
     * @param IdentityInterface $identity
     */
    public function update(MappingInterface $mapping, IdentityInterface $identity)
    {
        $this->adapter->update($this->table, $mapping, $this->makeSelectionFactory($identity));
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