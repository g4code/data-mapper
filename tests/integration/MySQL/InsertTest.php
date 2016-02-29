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

    public function testException()
    {
        $this->expectException('\Exception');
        $this->expectExceptionCode(101);
        $this->expectExceptionMessageRegExp('~^42\:\sSQLSTATE\[.*$~xius');

        $this->getBuilder()
            ->table($this->getTableName() . '_fail')
            ->build()
            ->insert($this->makeMapping());
    }

    public function getTableName()
    {
        return 'test_insert';
    }
}