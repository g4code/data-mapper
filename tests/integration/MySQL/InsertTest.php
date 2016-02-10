<?php

namespace G4\DataMapper\Test\Integration\MySQL;


class InsertTest extends TestCase
{

    public function testInsert()
    {
        $this->makeMapper()->insert($this->makeMapping());

        $rawData = $this->makeMapper()->find($this->makeIdentityById());

        $this->assertEquals(1, $rawData->count());
        $this->assertEquals($this->getData(), $rawData->getOne());
    }

    public function getTableName()
    {
        return 'test_insert';
    }
}