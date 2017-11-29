<?php

namespace G4\DataMapper\Test\Integration\MySQL;

use G4\DataMapper\Engine\MySQL\MySQLTableName;
use G4\DataMapper\Exception\MySQLMapperException;

class InsertTest extends TestCase
{

    public function testInsert()
    {
        $this->makeMapper()->insert($this->makeMapping());

        $rawData = $this->makeMapper()->find($this->makeIdentityById());

        $this->assertEquals(1, $rawData->count());
        $this->assertEquals($this->getData(), $rawData->getOne());
    }

    public function testException()
    {
        $this->expectException(MySQLMapperException::class);

        $this->getBuilder()
            ->collectionName(new MySQLTableName($this->getTableName() . '_fail'))
            ->buildMapper()
            ->insert($this->makeMapping());
    }

    public function getTableName()
    {
        return 'test_insert';
    }
}