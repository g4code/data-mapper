<?php

namespace G4\DataMapper;

use G4\DataMapper\Common\Bulk;
use G4\DataMapper\Engine\MySQL\MySQLClientFactory;
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
    private $dataSet;

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
     * @param array $params
     * @return Builder
     */
    public function engineMySQL(array $params)
    {
        $this->adapter = new MySQLAdapter(new MySQLClientFactory($params));
        return $this;
    }

    /**
     * @deprecated use buildMapper() instead
     * @throws \Exception
     * @return MapperInterface
     */
    public function build()
    {
        return $this->buildMapper();
    }

    /**
     * @return MapperInterface
     * @throws \Exception
     */
    public function buildMapper()
    {
        $this->validateDependencies();
        return $this->strategy();
    }

    /**
     * @return Bulk
     * @throws \Exception
     */
    public function buildBulk()
    {
        $this->validateDependencies();
        return new Bulk($this->adapter, $this->dataSet);
    }

    /**
     * @param string $table
     * @return Builder
     */
    public function table($table)
    {
        $this->dataSet = $table;
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
                $mapper = new MySQLMapper($this->adapter, $this->dataSet);
                break;
            default:
                throw new \Exception('Unknown engine', 601);
        }
        return $mapper;
    }

    private function validateDependencies()
    {
        if (!$this->adapter instanceof AdapterInterface) {
            throw new \Exception('Adapter instance must implement AdapterInterface', 601);
        }

        if ($this->dataSet === null) {
            throw new \Exception('DataSet cannot be emty', 601);
        }
    }
}