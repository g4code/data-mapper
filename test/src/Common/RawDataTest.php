<?php

use G4\DataMapper\Common\RawData;

class RawDataTest extends PHPUnit_Framework_TestCase
{

    private $data;

    private $rawData;

    private $total;

    protected function setUp()
    {
        $this->data = [
            'id' => 1,
            'data' => 'lorem ipsum',
        ];

        $this->total = 9;

        $this->rawData = new RawData($this->data, $this->total);
    }

    protected function tearDown()
    {
        $this->data = null;
        $this->total = null;
        $this->rawData = null;
    }

    public function testGetData()
    {
        $this->assertEquals($this->data, $this->rawData->getData());
    }

    public function testGetTotal()
    {
        $this->assertEquals($this->total, $this->rawData->getTotal());
    }
}