<?php

namespace G4\DataMapper\Test\Integration\Elasticsearch;

class InsertTest extends TestCase
{
    public function testInsert()
    {
        $this->makeMapper()->insert($this->makeMapping());

        sleep(2);

        $rawData = $this->makeMapper()->find($this->makeIdentityById());

        $this->assertEquals(1, $rawData->count());
        $this->assertArraySubset($this->getData(), $rawData->getOne());

        $this->makeMapper()->delete($this->makeIdentityById());
    }

    public function getCollectionName()
    {
        return 'user';
    }
}
