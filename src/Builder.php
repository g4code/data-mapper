<?php

namespace G4\DataMapper;

use G4\Factory\CreateInterface;
use G4\DataMapper\Common\AdapterInterface;
use G4\DataMapper\Common\MapperInterface;
use G4\DataMapper\Engine\MySQL\MySQLAdapter;
use G4\DataMapper\Engine\MySQL\MySQLMapper;

class Builder
{

    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * @var string
     */
    private $type;

    /**
     * @return Builder
     */
    public static function create()
    {
        return new static();
    }

    /**
     * @param AdapterInterface $adapter
     * @return Builder
     */
    public function adapter(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
        return $this;
    }

    /**
     * @throws \Exception
     * @return MapperInterface
     */
    public function build()
    {
        if (!$this->adapter instanceof AdapterInterface) {
            throw new \Exception('Adapter instance must implement AdapterInterface', 601);
        }

        if ($this->type === null) {
            throw new \Exception('Type must be set', 601);
        }

        return $this->strategy();
    }

    /**
     * @param string $type
     * @return Builder
     */
    public function type($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @throws \Exception
     * @return MapperInterface
     */
    private function strategy()
    {
        switch (true) {
            case $this->adapter instanceof MySQLAdapter:
                $mapper = new MySQLMapper($this->adapter, $this->type);
                break;
            default:
                throw new \Exception('Unknown engine', 601);
        }
        return $mapper;
    }
}