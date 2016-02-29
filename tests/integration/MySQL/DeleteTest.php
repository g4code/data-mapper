<?php

namespace G4\DataMapper\Test\Integration\MySQL;


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
        $this->expectException('\Exception');
        $this->expectExceptionCode(101);
        $this->expectExceptionMessageRegExp('~^42\:\sSQLSTATE\[.*$~xius');

        $this->getBuilder()
            ->table($this->getTableName() . '_fail')
            ->build()
            ->delete($this->makeIdentityById());
    }

    public function getTableName()
    {
        return 'test_delete';
    }
}
