<?php

namespace G4\DataMapper\Engine\MySQL;

use G4\DataMapper\Common\CollectionNameInterface;
use G4\DataMapper\Exception\TableNameException;

class MySQLTableName implements CollectionNameInterface
{

    /**
     * @var string
     */
    private $tableName;

    /**
     * TableName constructor.
     * @param $tableName string
     */
    public function __construct($tableName)
    {
        if (!is_string($tableName) || strlen($tableName) === 0) {
            throw new TableNameException();
        }
        $this->tableName = $tableName;
    }

    public function __toString()
    {
        return $this->tableName;
    }
}