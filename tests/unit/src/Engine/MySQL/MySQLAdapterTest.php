<?php

use G4\DataMapper\Engine\MySQL\MySQLAdapter;

class MySQLAdapterTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var MySQLAdapter
     */
    private $adapter;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $clientMock;


    protected function setUp()
    {
        $this->clientMock = $this->getMockBuilder('\Zend_Db_Adapter_Mysqli')
            ->disableOriginalConstructor()
            ->setMethods(['insert', 'delete', 'update', 'select', 'fetchAll', 'fetchOne', 'query', 'quote'])
            ->getMock();

        $this->adapter = new MySQLAdapter($this->getMockForMySQLClientFactory());
    }

    protected function tearDown()
    {
        $this->adapter = null;
        $this->clientMock = null;
    }

    public function testDelete()
    {
        $this->clientMock
            ->expects($this->once())
            ->method('delete');

        $selectionFactoryMock = $this->getMockBuilder('\G4\DataMapper\Engine\MySQL\MySQLSelectionFactory')
            ->disableOriginalConstructor()
            ->getMock();

        $selectionFactoryMock
            ->expects($this->once())
            ->method('where');

        $this->adapter->delete('data', $selectionFactoryMock);
    }

    public function testEmptyDataForInsert()
    {
        $this->clientMock->expects($this->never())
            ->method('insert');

        $mappingStub = $this->getMockForMappings();
        $mappingStub
            ->expects($this->once())
            ->method('map')
            ->willReturn([]);

        $this->setExpectedException('\Exception', 'Empty data for insert');
        $this->adapter->insert('data', $mappingStub);
    }

    public function testEmptyDataForUpdate()
    {
        $this->clientMock->expects($this->never())
            ->method('update');

        $mappingStub = $this->getMockForMappings();
        $mappingStub
            ->expects($this->once())
            ->method('map')
            ->willReturn([]);

        $selectionFactoryStub = $this->getMock('G4\DataMapper\Common\SelectionFactoryInterface');

        $this->setExpectedException('\Exception', 'Empty data for update');
        $this->adapter->update('data', $mappingStub, $selectionFactoryStub);
    }

    public function testInsert()
    {
        $this->clientMock
            ->expects($this->once())
            ->method('insert');

        $mappingStub = $this->getMockForMappings();
        $mappingStub
            ->expects($this->once())
            ->method('map')
            ->willReturn(['id' => 1]);

        $this->adapter->insert('data', $mappingStub);
    }

    public function testInsertBulk()
    {
        $this->clientMock
            ->expects($this->once())
            ->method('query')
            ->with($this->equalTo("INSERT INTO data ('id','ts') VALUES (123,456),(789,321)"));

        $mappingStubFirst = $this->getMockForMappings();
        $mappingStubFirst
            ->expects($this->any())
            ->method('map')
            ->willReturn([
                'id' => 123,
                'ts' => 456
            ]);

        $mappingStubSecond = $this->getMockForMappings();
        $mappingStubSecond
            ->expects($this->any())
            ->method('map')
            ->willReturn([
                'id' => 789,
                'ts' => 321
            ]);

        $this->adapter->insertBulk('data', new \ArrayIterator([$mappingStubFirst, $mappingStubSecond]));
    }

    public function testSelect()
    {
        $zendDbSelectMock = $this->getMockBuilder('\Zend_Db_Select')
            ->disableOriginalConstructor()
            ->getMock();
        $zendDbSelectMock
            ->expects($this->exactly(2))
            ->method('from')
            ->willReturnSelf();
        $zendDbSelectMock
            ->expects($this->exactly(2))
            ->method('where')
            ->willReturnSelf();
        $zendDbSelectMock
            ->expects($this->once())
            ->method('order')
            ->willReturnSelf();
        $zendDbSelectMock
            ->expects($this->once())
            ->method('limit')
            ->willReturnSelf();
        $zendDbSelectMock
            ->expects($this->exactly(2))
            ->method('group')
            ->willReturnSelf();

        $this->clientMock
            ->expects($this->exactly(2))
            ->method('select')
            ->willReturn($zendDbSelectMock);
        $this->clientMock
            ->expects($this->once())
            ->method('fetchAll')
            ->willReturn([['data' => 1]]);
        $this->clientMock
            ->expects($this->once())
            ->method('fetchOne')
            ->willReturn(1);

        $selectionFactoryStub = $this->getMockBuilder('\G4\DataMapper\Engine\MySQL\MySQLSelectionFactory')
            ->disableOriginalConstructor()
            ->getMock();

        $this->assertInstanceOf('\G4\DataMapper\Common\RawData', $this->adapter->select('data', $selectionFactoryStub));
    }

    public function testUpdate()
    {
        $this->clientMock->expects($this->once())
            ->method('update');

        $mappingMock = $this->getMockForMappings();
        $mappingMock
            ->expects($this->once())
            ->method('map')
            ->willReturn(['id' => 1]);

        $selectionFactoryMock = $this->getMockBuilder('G4\DataMapper\Common\SelectionFactoryInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $selectionFactoryMock
            ->expects($this->once())
            ->method('where');

        $this->adapter->update('data', $mappingMock, $selectionFactoryMock);
    }

    public function testQueryForDelete()
    {
        $this->clientMock
            ->expects($this->once())
            ->method('query')
            ->willReturn(true);

        $this->assertNull($this->adapter->query('delete from table_name where 1'));
    }

    public function testQueryForSelect()
    {
        $this->clientMock
            ->expects($this->once())
            ->method('fetchAll')
            ->willReturn([['data' => 1]]);

        $this->assertInstanceOf('\G4\DataMapper\Common\RawData', $this->adapter->query('select * from table_name where 1'));
    }

    public function testQueryWithEmptyQuery()
    {
        $this->expectException('\Exception');
        $this->expectExceptionCode(101);
        $this->expectExceptionMessage('Query cannot be empty');

        $this->adapter->query('');
    }

    public function testQueryWithUnknown()
    {
        $this->expectException('\Exception');
        $this->expectExceptionCode(101);
        $this->expectExceptionMessage('Query does not match a known pattern (insert, delete, update, select)');

        $this->adapter->query('tralala');
    }

    private function getMockForMySQLClientFactory()
    {
        $clientFactoryStub = $this->getMockBuilder('\G4\DataMapper\Engine\MySQL\MySQLClientFactory')
            ->disableOriginalConstructor()
            ->getMock();

        $clientFactoryStub->method('create')
            ->willReturn($this->clientMock);

        return $clientFactoryStub;
    }

    private function getMockForMappings()
    {
        $mappingStub = $this->getMockBuilder('\G4\DataMapper\Common\MappingInterface')
            ->getMock();
        return $mappingStub;
    }
}