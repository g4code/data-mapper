<?php

use G4\DataMapper\Engine\MySQL\MySQLMapper;
use G4\DataMapper\Exception\MySQLMapperException;

class MySQLMapperTest extends PHPUnit_Framework_TestCase
{
    const MYSQL_DATA_MAPPER_ERROR_MESSAGE = 'MySQL Mapper error';


    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $adapterMock;

    /**
     * @var MySQLMapper
     */
    private $mapper;

    private $mappingMock;

    private $tableNameMock;


    protected function setUp()
    {
        $this->adapterMock = $this->getMockBuilder(\G4\DataMapper\Engine\MySQL\MySQLAdapter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mappingMock = $this->getMockBuilder(\G4\DataMapper\Common\MappingInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->tableNameMock = $this->getMockBuilder(\G4\DataMapper\Engine\MySQL\MySQLTableName::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mapper = new MySQLMapper($this->adapterMock, $this->tableNameMock);
    }

    protected function tearDown()
    {
        $this->adapterMock      = null;
        $this->mappingMock      = null;
        $this->mapper           = null;
        $this->tableNameMock    = null;
    }

    public function testDelete()
    {
        $identityStub = $this->getMock(\G4\DataMapper\Common\IdentityInterface::class);

        $this->adapterMock
            ->expects($this->once())
            ->method('delete');

        $this->mapper->delete($identityStub);
    }

    public function testDeleteException()
    {
        $identityStub = $this->getMock(\G4\DataMapper\Common\IdentityInterface::class);

        $this->adapterMock
            ->expects($this->once())
            ->method('delete')
            ->will($this->throwException(new MySQLMapperException(self::MYSQL_DATA_MAPPER_ERROR_MESSAGE)));

        $this->expectException(\G4\DataMapper\Exception\MySQLMapperException::class);

        $this->mapper->delete($identityStub);
    }

    public function testFind()
    {
        $rawDataStub = $this->getMockBuilder(\G4\DataMapper\Common\RawData::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->adapterMock
            ->expects($this->once())
            ->method('select')
            ->willReturn($rawDataStub);

        $this->assertSame($rawDataStub, $this->mapper->find($this->getMock(\G4\DataMapper\Common\Identity::class)));
    }

    public function testFindException()
    {
        $this->adapterMock
            ->expects($this->once())
            ->method('select')
            ->will($this->throwException(new MySQLMapperException(self::MYSQL_DATA_MAPPER_ERROR_MESSAGE)));

        $this->expectException(\G4\DataMapper\Exception\MySQLMapperException::class);

        $this->mapper->find($this->getMock(\G4\DataMapper\Common\Identity::class));
    }

    public function testInsert()
    {
        $this->adapterMock
            ->expects($this->once())
            ->method('insert')
            ->with($this->equalTo($this->tableNameMock), $this->equalTo($this->mappingMock));

        $this->mapper->insert($this->mappingMock);
    }

    public function testInsertException()
    {
        $this->adapterMock
            ->expects($this->once())
            ->method('insert')
            ->with($this->equalTo($this->tableNameMock), $this->equalTo($this->mappingMock))
            ->will($this->throwException(new MySQLMapperException(self::MYSQL_DATA_MAPPER_ERROR_MESSAGE)));

        $this->expectException(\G4\DataMapper\Exception\MySQLMapperException::class);

        $this->mapper->insert($this->mappingMock);
    }

    public function testUpdate()
    {
        $this->adapterMock
            ->expects($this->once())
            ->method('update')
            ->with($this->equalTo($this->tableNameMock), $this->equalTo($this->mappingMock));

        $this->mapper->update($this->mappingMock, $this->getMock(\G4\DataMapper\Common\Identity::class));
    }

    public function testUpsert()
    {
        $this->adapterMock
            ->expects($this->once())
            ->method('upsert')
            ->with($this->equalTo($this->tableNameMock), $this->equalTo($this->mappingMock));

        $this->mapper->upsert($this->mappingMock);
    }

    public function testUpsertException()
    {
        $this->adapterMock
            ->expects($this->once())
            ->method('upsert')
            ->will($this->throwException(new MySQLMapperException(self::MYSQL_DATA_MAPPER_ERROR_MESSAGE)));

        $this->expectException(\G4\DataMapper\Exception\MySQLMapperException::class);

        $this->mapper->upsert($this->mappingMock);
    }

    public function testExceptionUpdate()
    {
        $this->adapterMock
            ->expects($this->once())
            ->method('update')
            ->with($this->equalTo($this->tableNameMock), $this->equalTo($this->mappingMock))
            ->will($this->throwException(new MySQLMapperException(self::MYSQL_DATA_MAPPER_ERROR_MESSAGE)));

        $this->expectException(\G4\DataMapper\Exception\MySQLMapperException::class);

        $this->mapper->update($this->mappingMock, $this->getMock(\G4\DataMapper\Common\Identity::class));
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
            ->willThrowException(new MySQLMapperException(self::MYSQL_DATA_MAPPER_ERROR_MESSAGE));

        $this->expectException(\G4\DataMapper\Exception\MySQLMapperException::class);

        $this->mapper->query('sql');
    }

    public function testSimpleQuery()
    {
        $this->adapterMock
            ->expects($this->once())
            ->method('simpleQuery')
            ->willReturn(true);

        $this->mapper->simpleQuery('query sql');
    }

    public function testSimpleQueryException()
    {
        $this->adapterMock
            ->expects($this->once())
            ->method('simpleQuery')
            ->willThrowException(new MySQLMapperException(self::MYSQL_DATA_MAPPER_ERROR_MESSAGE));

        $this->expectException(\G4\DataMapper\Exception\MySQLMapperException::class);

        $this->mapper->simpleQuery('query sql');
    }
}
