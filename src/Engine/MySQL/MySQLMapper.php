<?php

namespace G4\DataMapper\Engine\MySQL;

use G4\DataMapper\Common\AdapterInterface;
use G4\DataMapper\Common\MapperInterface;
use G4\DataMapper\Common\MappingInterface;
use G4\DataMapper\Common\SelectionIdentityInterface;
use G4\Factory\ReconstituteInterface;

class MySQLMapper implements MapperInterface
{

    private $adapter;

    private $type;

    public function __construct(AdapterInterface $adapter, $type)
    {
        $this->adapter = $adapter;
        $this->type    = $type;
    }

    public function delete(MappingInterface $mappings)
    {

    }

    public function findAll(SelectionIdentityInterface $identity, ReconstituteInterface $factory)
    {

    }

    public function findOne(SelectionIdentityInterface $identity, ReconstituteInterface $factory)
    {

    }

    public function insert(MappingInterface $mappings)
    {
        $this->adapter->insert($mappings->map());
    }

    public function update(MappingInterface $mappings)
    {

    }
}