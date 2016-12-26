<?php

namespace G4\DataMapper;

use G4\DataMapper\Common\Bulk;
use G4\DataMapper\Engine\MySQL\MySQLClientFactory;
use G4\DataMapper\Common\AdapterInterface;
use G4\DataMapper\Common\MapperInterface;
use G4\DataMapper\Engine\MySQL\MySQLAdapter;
use G4\DataMapper\Engine\MySQL\MySQLMapper;
use G4\DataMapper\Engine\MySQL\MySQLTransaction;

class Builder
{

    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * @var string
     */
    private $dataSetName;

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
        trigger_error("Method build() is deprecated, use buildMapper() instead.", E_USER_DEPRECATED);
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
        return new Bulk($this->adapter, $this->dataSetName);
    }

    //TODO: Drasko - change this!!!
    public function buildTransaction()
    {
        $this->validateDependencies();
        return new MySQLTransaction($this->adapter);
    }

    /**
     * @param string $table
     * @return Builder
     */
    public function table($table)
    {
        $this->dataSetName = $table;
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
                $mapper = new MySQLMapper($this->adapter, $this->dataSetName);
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

        if ($this->dataSetName === null) {
            throw new \Exception('DataSet cannot be emty', 601);
        }
    }
}