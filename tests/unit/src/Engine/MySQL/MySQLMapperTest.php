<?php

use G4\DataMapper\Engine\MySQL\MySQLMapper;
use G4\DataMapper\Engine\MySQL\MySQLAdapter;
use G4\DataMapper\Common\MappingInterface;

class MySQLMapperTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
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
        $identityStub = $this->getMock('\G4\DataMapper\Common\IdentityInterface');

        $this->adapterMock
            ->expects($this->once())
            ->method('delete');

        $this->mapper->delete($identityStub);
    }

    public function testDeleteException()
    {
        $identityStub = $this->getMock('\G4\DataMapper\Common\IdentityInterface');

        $this->adapterMock
            ->expects($this->once())
            ->method('delete')
            ->will($this->throwException(new \Exception()));

        $this->expectException('\Exception');

        $this->mapper->delete($identityStub);
    }

    public function testFind()
    {
        $rawDataStub = $this->getMockBuilder('\G4\DataMapper\Common\RawData')
            ->disableOriginalConstructor()
            ->getMock();

        $this->adapterMock
            ->expects($this->once())
            ->method('select')
            ->willReturn($rawDataStub);

        $this->assertSame($rawDataStub, $this->mapper->find($this->getMock('\G4\DataMapper\Common\Identity')));
    }

    public function testFindException()
    {
        $this->adapterMock
            ->expects($this->once())
            ->method('select')
            ->will($this->throwException(new \Exception()));

        $this->expectException('\Exception');

        $this->mapper->find($this->getMock('\G4\DataMapper\Common\Identity'));
    }

    public function testInsert()
    {
        $this->adapterMock
            ->expects($this->once())
            ->method('insert')
            ->with($this->equalTo('users'), $this->equalTo($this->mappingMock));

        $this->mapper->insert($this->mappingMock);
    }

    public function testInsertException()
    {
        $this->adapterMock
            ->expects($this->once())
            ->method('insert')
            ->with($this->equalTo('users'), $this->equalTo($this->mappingMock))
            ->will($this->throwException(new \Exception()));

        $this->expectException('\Exception');

        $this->mapper->insert($this->mappingMock);
    }

    public function testUpdate()
    {
        $this->adapterMock
            ->expects($this->once())
            ->method('update')
            ->with($this->equalTo('users'), $this->equalTo($this->mappingMock));

        $this->mapper->update($this->mappingMock, $this->getMock('\G4\DataMapper\Common\Identity'));
    }

    public function testExceptionUpdate()
    {
        $this->adapterMock
            ->expects($this->once())
            ->method('update')
            ->with($this->equalTo('users'), $this->equalTo($this->mappingMock))
            ->will($this->throwException(new \Exception()));

        $this->expectException('\Exception');

        $this->mapper->update($this->mappingMock, $this->getMock('\G4\DataMapper\Common\Identity'));
    }

    public function testQuery()
    {
        $this->adapterMock
            ->expects($this->once())
            ->method('query')
            ->willReturn(true);

        $this->assertTrue($this->mapper->query('query sql'));
    }

    public function testQueryException()
    {
        $this->adapterMock
            ->expects($this->once())
            ->method('query')
            ->willThrowException(new \Exception());

        $this->expectException('\Exception');

        $this->mapper->query('sql');
    }
}