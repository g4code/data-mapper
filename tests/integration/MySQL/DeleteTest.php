<?php

namespace G4\DataMapper\Test\Integration\MySQL;

use G4\DataMapper\Engine\MySQL\MySQLTableName;
use G4\DataMapper\Exception\MySQLMapperException;

class DeleteTest extends TestCase
{

    public function testDelete()
    {
        $rawData = $this->makeMapper()->find($this->makeIdentityById());

        $this->assertEquals(1, $rawData->count());

        $this->makeMapper()->delete($this->makeIdentityById());

        $rawData = $this->makeMapper()->find($this->makeIdentityById());

        $this->assertEquals(0, $rawData->count());
        $this->assertEquals(null, $rawData->getOne());
    }

    public function testException()
    {
        $this->expectException(MySQLMapperException::class);

        $this->getBuilder()
            ->collectionName(new MySQLTableName($this->getTableName() . '_fail'))
            ->buildMapper()
            ->delete($this->makeIdentityById());
    }

    public function getTableName()
    {
        return 'test_delete';
    }
}
