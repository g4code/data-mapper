<?php

namespace G4\DataMapper\Test\Integration\MySQL;

use G4\DataMapper\Builder;
use G4\DataMapper\Engine\MySQL\MySQLAdapter;
use G4\DataMapper\Engine\MySQL\MySQLClientFactory;
use G4\DataMapper\Common\Identity;
use G4\DataMapper\Test\Integration\MySQL\TestCase;

class InsertTest extends TestCase
{

    public function testInsert()
    {
        $this->makeMapper()->insert($this->makeMapping());

        $rawData = $this->makeMapper()->find($this->makeIdentityById());

        $this->assertEquals(1, $rawData->count());
        $this->assertEquals($this->getData(), $rawData->getOne());
    }

    public function testExceptionOnInsert()
    {
        $this->expectException('\Exception');
        $this->expectExceptionCode(101);
        $this->expectExceptionMessageRegExp('~^42\:\sSQLSTATE\[.*$~xius');

        $this->getBuilder()
            ->type('test_insert_fail')
            ->build()
            ->insert($this->makeMapping());
    }

    public function makeMapper()
    {
        return $this->getBuilder()
            ->type('test_insert')
            ->build();
    }

}