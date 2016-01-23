<?php

namespace G4\DataMapper\Engine\MySQL;

use G4\DataMapper\Common\AdapterInterface;
use G4\DataMapper\Common\MapperInterface;
use G4\DataMapper\Common\MappingInterface;
use G4\DataMapper\Common\IdentityInterface;

class MySQLMapper implements MapperInterface
{

    /**
     * @var MySQLAdapter
     */
    private $adapter;

    private $table;

    public function __construct(AdapterInterface $adapter, $table)
    {
        $this->adapter = $adapter;
        $this->table   = $table;
    }

    public function delete(IdentityInterface $identity)
    {
        $this->adapter->delete($this->table, $this->makeSelectionFactory($identity));
    }

    public function find(IdentityInterface $identity)
    {
        return $this->adapter->select($this->table, $this->makeSelectionFactory($identity));
    }

    public function insert(MappingInterface $mappings)
    {
        $this->adapter->insert($this->table, $mappings);
    }

    public function update(MappingInterface $mapping, IdentityInterface $identity)
    {
        $this->adapter->update($this->table, $mapping, $this->makeSelectionFactory($identity));
    }

    private function makeSelectionFactory(IdentityInterface $identity)
    {
        return new MySQLSelectionFactory($identity);
    }
}