<?php

use G4\DataMapper\Engine\MySQL\MySQLTableName;

class MySQLTableNameTest extends PHPUnit_Framework_TestCase
{

    private $mySQLTableName;

    private $tableName;

    protected function setUp()
    {
        $this->tableName        = 'tralala';
        $this->mySQLTableName   = new MySQLTableName($this->tableName);
    }

    protected function tearDown()
    {
        $this->mySQLTableName   = null;
        $this->tableName        = null;
    }

    public function testTableNameException()
    {
        $this->expectException('\G4\DataMapper\Exception\TableNameException');

        new MySQLTableName(null);

        $this->expectException('\G4\DataMapper\Exception\TableNameException');
        new MySQLTableName('');

        $this->expectException('\G4\DataMapper\Exception\TableNameException');
        new MySQLTableName(123);
    }

    public function testToString()
    {
        $this->assertEquals($this->tableName, (string) $this->mySQLTableName);
    }
}