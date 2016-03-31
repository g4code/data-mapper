<?php

use G4\DataMapper\Common\Bulk;

class BulkTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $adapterMock;

    /**
     * @var Bulk
     */
    private $bulk;

    protected function setUp()
    {
        $this->adapterMock = $this->getMockBuilder('\G4\DataMapper\Common\AdapterInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->bulk = new Bulk($this->adapterMock, 'test_table');
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
        $this->assertInstanceOf('\G4\DataMapper\Common\MappingInterface', $this->bulk->getData()->current());
    }

    public function testInsert()
    {
        $this->adapterMock
            ->expects($this->once())
            ->method('insertBulk')
//            ->with('test_table_name', new \ArrayIterator([]))
        ;

        $this->bulk->insert();
    }

    private function getMappingMock()
    {
        $stub = $this->getMockBuilder('\G4\DataMapper\Common\MappingInterface')
            ->disableOriginalConstructor()
            ->getMock();
        return $stub;
    }
}