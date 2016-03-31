<?php

namespace G4\DataMapper\Common;

use G4\DataMapper\Common\MappingInterface;

class Bulk implements \Countable
{

    /**
     * @var array
     */
    private $data;

    public function __construct()
    {
        $this->data = [];
    }

    /**
     * @param MappingInterface $mapping
     * @return Bulk
     */
    public function add(MappingInterface $mapping)
    {
        $this->data[] = $mapping;
        return $this;
    }

    public function insert(){}

    public function update() {}

    public function upsert() {}

    /**
     * @return \ArrayIterator
     */
    public function getData()
    {
        return new \ArrayIterator($this->data);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->data);
    }
}