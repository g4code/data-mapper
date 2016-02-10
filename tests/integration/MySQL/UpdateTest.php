<?php

namespace G4\DataMapper\Test\Integration\MySQL;


class UpdateTest extends TestCase
{

    public function testUpdate()
    {
        $this->makeMapper()->update($this->makeMapping(), $this->makeIdentityById());

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
            ->type($this->getTableName() . '_fail')
            ->build()
            ->update($this->makeMapping(), $this->makeIdentityById());
    }

    public function getData()
    {
        $data = parent::getData();
        $data['title'] = 'Totally new title';
        return $data;
    }

    public function getTableName()
    {
        return 'test_update';
    }
}