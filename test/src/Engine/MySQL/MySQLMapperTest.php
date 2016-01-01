<?php

use G4\DataMapper\Engine\MySQL\MySQLMapper;
use G4\DataMapper\Engine\MySQL\MySQLAdapter;
use G4\DataMapper\Common\MappingInterface;

class MySQLMapperTest extends PHPUnit_Framework_TestCase
{

    private $adapterMock;

    /**
     * @var MySQLMapper
     */
    private $mapper;

    private $mappingMock;


    protected function setUp()
    {
        $this->adapterMock = $this->getMockBuilder('\G4\DataMapper\Engine\MySQL\MySQLAdapter')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mappingMock = $this->getMockBuilder('\G4\DataMapper\Common\MappingInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mapper = new MySQLMapper($this->adapterMock, 'users');
    }

    protected function tearDown()
    {
        $this->adapterMock = null;
        $this->mappingMock = null;
        $this->mapper      = null;
    }

    public function testDelete()
    {
        $this->adapterMock
            ->expects($this->once())
            ->method('delete')
            ->with($this->equalTo('users'), $this->equalTo($this->mappingMock));

        $this->mapper->delete($this->mappingMock);
    }

    public function testInsert()
    {
        $this->adapterMock
            ->expects($this->once())
            ->method('insert')
            ->with($this->equalTo('users'), $this->equalTo($this->mappingMock));

        $this->mapper->insert($this->mappingMock);
    }

    public function testUpdate()
    {
        $this->adapterMock
            ->expects($this->once())
            ->method('update')
            ->with($this->equalTo('users'), $this->equalTo($this->mappingMock));

        $this->mapper->update($this->mappingMock);
    }
}