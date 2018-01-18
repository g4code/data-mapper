<?php

namespace G4\DataMapper\Test\Integration\Solr;

class DeleteTest extends TestCase
{
    public function testDelete()
    {
        $this->makeMapper()->insert($this->makeMapping());

        $this->makeMapper()->delete($this->makeIdentityById());

        sleep(2);

        $rawData = $this->makeMapper()->find($this->makeIdentityById());

        $this->assertEquals(0, $rawData->count());
        $this->assertEquals(null, $rawData->getOne());
    }

    public function getCollectionName()
    {
        return 'integration_test';
    }
}
