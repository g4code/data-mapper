<?php

namespace G4\DataMapper\Common;

use G4\DataMapper\Common\MappingInterface;

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
     * @var string
     */
    private $type;


    public function __construct(AdapterInterface $adapter, $type)
    {
        $this->adapter = $adapter;
        $this->type    = $type;
        $this->data    = new \ArrayIterator([]);
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
            $this->adapter->insertBulk($this->type, $this->getData());
        } catch (\Exception $exception) {
            throw new \Exception($exception->getCode() . ': ' . $exception->getMessage(), 101);
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
    public function count()
    {
        return count($this->data);
    }
}