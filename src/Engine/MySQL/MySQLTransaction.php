<?php

namespace G4\DataMapper\Engine\MySQL;

use G4\DataMapper\Exception\DatabaseOperationException;

class MySQLTransaction
{

    /**
     * @var bool
     */
    private $active;

    /**
     * @var MySQLAdapter
     */
    private $adapter;

    /**
     * @param MySQLAdapter $adapter
     */
    public function __construct(MySQLAdapter $adapter)
    {
        $this->adapter = $adapter;
        $this->active  = false;
    }

    /**
     * @throws \Exception
     */
    public function begin()
    {
        if ($this->active) {
            throw new DatabaseOperationException('Database transaction has already started');
        }

        $this->active = true;
        $this->adapter->beginTransaction();
    }

    public function commit()
    {
        $this->active = false;
        $this->adapter->commitTransaction();
    }

    public function rollBack()
    {
        $this->active = false;
        $this->adapter->rollBackTransaction();
    }
}
