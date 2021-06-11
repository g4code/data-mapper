<?php

use G4\DataMapper\Common\SimpleRawData;

class SimpleRawDataTest extends PHPUnit_Framework_TestCase
{
    private $data;

    private $rawData;

    protected function setUp()
    {
        $this->data = [
            [
                'id' => 1,
                'data' => 'lorem ipsum',
            ]
        ];

        $this->rawData = new SimpleRawData($this->data);
    }

    protected function tearDown()
    {
        $this->data = null;
        $this->rawData = null;
    }

    public function testCount()
    {
        $this->assertEquals(1, $this->rawData->count());
    }

    public function testGetAll()
    {
        $this->assertEquals($this->data, $this->rawData->getAll());
    }

    public function testGetOne()
    {
        $this->assertEquals($this->data[0], $this->rawData->getOne());
    }
}
