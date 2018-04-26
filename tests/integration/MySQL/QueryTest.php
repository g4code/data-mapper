<?php

namespace G4\DataMapper\Test\Integration\MySQL;

use G4\DataMapper\Common\Identity;

class QueryTest extends TestCase
{

    public function testQueryShow()
    {
        $rawData = $this->makeMapper()->query('SELECT * FROM test_insert');

        $this->assertInstanceOf('\G4\DataMapper\Common\RawData', $rawData);
    }

    public function testQueryInsert()
    {
        $this->makeMapper()->query("
          INSERT INTO test_insert (id, title, content) VALUES ('54321', 'tralala', 'tralalalala');
      ");

        $identity = new Identity();
        $identity
            ->field('id')
            ->equal(54321);

        $rawData = $this->makeMapper()->find($identity);

        $this->assertEquals(1, $rawData->count());
        $this->assertEquals(54321, $rawData->getOne()['id']);
    }

    public function getTableName()
    {
        return 'test_insert';
    }
}
