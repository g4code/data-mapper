<?php

namespace G4\DataMapper\Test\Integration\Solr;

class UpdateTest extends TestCase
{
    public function testUpdate()
    {
        $this->makeMapper()->insert($this->makeMapping());

        $this->makeMapper()->update($this->makeMapping(), $this->makeIdentityById());

        sleep(2);

        $rawData = $this->makeMapper()->find($this->makeIdentityById());

        $this->assertEquals(1, $rawData->count());
        $this->assertArraySubset($this->getData(), $rawData->getOne());

        $this->makeMapper()->delete($this->makeIdentityById());
    }

    public function getCollectionName()
    {
        return 'nd_api_messages';
    }

    public function getData()
    {
        $data = parent::getData();
        $data['message'] = 'This is updated message';
        $data['type']    = 5;

        return $data;
    }
}
