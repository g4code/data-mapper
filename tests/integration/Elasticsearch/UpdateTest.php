<?php

namespace G4\DataMapper\Test\Integration\Elasticsearch;

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
        return 'user';
    }

    public function getData()
    {
        $data = parent::getData();
        $data['first_name'] = 'Test Updated';
        $data['last_name']  = 'User Updated';
        $data['status']    = 2;

        return $data;
    }
}
