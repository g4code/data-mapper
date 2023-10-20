<?php

use G4\DataMapper\Engine\MySQL\MySQLTableName;

class MySQLTableNameTest extends \PHPUnit\Framework\TestCase
{

    private $mySQLTableName;

    private $tableName;

    protected function setUp(): void
    {
        $this->tableName        = 'tralala';
        $this->mySQLTableName   = new MySQLTableName($this->tableName);
    }

    protected function tearDown(): void
    {
        $this->mySQLTableName   = null;
        $this->tableName        = null;
    }

    public function testTableNameException()
    {
        $this->expectException(\G4\DataMapper\Exception\TableNameException::class);

        new MySQLTableName(null);

        $this->expectException(\G4\DataMapper\Exception\TableNameException::class);
        new MySQLTableName('');

        $this->expectException(\G4\DataMapper\Exception\TableNameException::class);
        new MySQLTableName(123);
    }

    public function testToString()
    {
        $this->assertEquals($this->tableName, (string) $this->mySQLTableName);
    }
}
