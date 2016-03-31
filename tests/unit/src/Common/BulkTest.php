<?php

use G4\DataMapper\Common\Bulk;

class BulkTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Bulk
     */
    private $bulk;

    protected function setUp()
    {
        $this->bulk = new Bulk();
    }

    protected function tearDown()
    {
        $this->bulk = null;
    }

    public function testAdd()
    {
        $this->assertEquals(0, count($this->bulk));
        $this->bulk->add($this->getMappingMock());
        $this->assertEquals(1, count($this->bulk));
        $this->bulk->add($this->getMappingMock());
        $this->assertEquals(2, count($this->bulk));
    }

    public function testGetData()
    {
        $this->bulk->add($this->getMappingMock());
        $this->assertInstanceOf('\ArrayIterator', $this->bulk->getData());
        $this->assertEquals(1, count($this->bulk->getData()));
    }

    private function getMappingMock()
    {
        $stub = $this->getMockBuilder('\G4\DataMapper\Common\MappingInterface')
            ->disableOriginalConstructor()
            ->getMock();
        return $stub;
    }
}