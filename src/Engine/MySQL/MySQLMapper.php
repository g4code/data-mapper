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

    public function delete(MappingInterface $mappings)
    {
        $this->adapter->delete($this->table, $mappings);
    }

    public function find(IdentityInterface $identity)
    {
        return $this->adapter->select($this->table, $this->makeSelectionFactory($identity));
    }

    public function insert(MappingInterface $mappings)
    {
        $this->adapter->insert($this->table, $mappings);
    }

    public function update(MappingInterface $mappings)
    {
        $this->adapter->update($this->table, $mappings);
    }

    private function makeSelectionFactory(IdentityInterface $identity)
    {
        return new MySQLSelectionFactory($identity);
    }
}