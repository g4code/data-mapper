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

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $tableNameMock;


    protected function setUp()
    {
        $this->adapterMock = $this->getMockBuilder('\G4\DataMapper\Common\AdapterInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->tableNameMock = $this->getMockBuilder('\G4\DataMapper\Engine\MySQL\MySQLTableName')
            ->disableOriginalConstructor()
            ->getMock();

        $this->tableNameMock
            ->expects($this->any())
            ->method('__toString')
            ->willReturn('test_table');

        $this->bulk = new Bulk($this->adapterMock, $this->tableNameMock);
    }

    protected function tearDown()
    {
        $this->adapterMock      = null;
        $this->tableNameMock    = null;
        $this->bulk             = null;
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
            ->with($this->equalTo($this->tableNameMock), $this->equalTo(new \ArrayIterator([])));

        $this->bulk->insert();
    }

    public function testInsertException()
    {
        $this->adapterMock
            ->expects($this->once())
            ->method('insertBulk')
            ->willThrowException(new \Exception());

        $this->expectException('\Exception');

        $this->bulk->insert();
    }

    public function testUpsert()
    {
        $this->adapterMock
            ->expects($this->once())
            ->method('upsertBulk')
            ->with($this->equalTo($this->tableNameMock), $this->equalTo(new \ArrayIterator([])));

        $this->bulk->upsert();
    }

    public function testUpsertException()
    {
        $this->adapterMock
            ->expects($this->once())
            ->method('upsertBulk')
            ->willThrowException(new \Exception());

        $this->expectException('\Exception');

        $this->bulk->upsert();
    }

    private function getMappingMock()
    {
        $stub = $this->getMockBuilder('\G4\DataMapper\Common\MappingInterface')
            ->disableOriginalConstructor()
            ->getMock();
        return $stub;
    }
}