<?php

use G4\DataMapper\Engine\MySQL\MySQLAdapter;

class MySQLAdapterTest extends PHPUnit_Framework_TestCase
{

    private $adapter;

    private $clientMock;


    protected function setUp()
    {
        $this->clientMock = $this->getMockBuilder('\Zend_Db_Adapter_Mysqli')
            ->disableOriginalConstructor()
            ->setMethods(['insert', 'delete', 'update', 'select', 'fetchAll', 'fetchOne'])
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

        $this->setExpectedException('\Exception', 'Empty data for update');
        $this->adapter->update('data', $mappingStub);


        $this->setExpectedException('\Exception', 'Empty identifiers for update');
        $this->adapter->update('data', $mappingStub);
    }

    public function testEmptyIdentifiersForUpdate()
    {
        $this->clientMock->expects($this->never())
            ->method('update');

        $mappingStub = $this->getMockForMappings();
        $mappingStub
            ->expects($this->once())
            ->method('map')
            ->willReturn(['id' => 1]);

        $mappingStub
            ->expects($this->once())
            ->method('identifiers')
            ->willReturn([]);

        $this->setExpectedException('\Exception', 'Empty identifiers for update');
        $this->adapter->update('data', $mappingStub);
    }

    public function testInsert()
    {
        $this->clientMock->expects($this->once())
            ->method('insert');

        $mappingStub = $this->getMockForMappings();
        $mappingStub
            ->expects($this->once())
            ->method('map')
            ->willReturn(['id' => 1]);

        $this->adapter->insert('data', $mappingStub);
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

        $mappingStub = $this->getMockForMappings();
        $mappingStub
            ->expects($this->once())
            ->method('identifiers')
            ->willReturn(['id' => 1]);
        $mappingStub
            ->expects($this->once())
            ->method('map')
            ->willReturn(['id' => 1]);

        $this->adapter->update('data', $mappingStub);
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