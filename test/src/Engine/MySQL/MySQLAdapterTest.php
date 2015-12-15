<?php

use G4\DataMapper\Engine\MySQL\MySQLAdapter;

class MySQLAdapterTest extends PHPUnit_Framework_TestCase
{

    private $adapter;

    private $clientStub;


    protected function setUp()
    {
        $this->clientStub = $this->getMockBuilder('\Zend_Db_Adapter_Mysqli')
            ->disableOriginalConstructor()
            ->setMethods(['insert', 'delete', 'update', 'select', 'fetchAll', 'fetchOne'])
            ->getMock();

        $this->adapter = new MySQLAdapter($this->getMockForMySQLClientFactory());
    }

    protected function tearDown()
    {
        $this->adapter = null;
        $this->clientStub = null;
    }

    public function testDelete()
    {
        $this->clientStub->expects($this->once())
            ->method('delete');

        $mappingStub = $this->getMockForMappings();
        $mappingStub
            ->expects($this->once())
            ->method('identifiers')
            ->willReturn(['id' => 1]);

        $this->adapter->delete('data', $mappingStub);
    }

    public function testEmptyDataForDelete()
    {
        $this->clientStub->expects($this->never())
            ->method('delete');

        $mappingStub = $this->getMockForMappings();
        $mappingStub
            ->expects($this->once())
            ->method('identifiers')
            ->willReturn([]);

        $this->setExpectedException('\Exception', 'Empty identifiers for delete');
        $this->adapter->delete('data', $mappingStub);
    }

    public function testEmptyDataForInsert()
    {
        $this->clientStub->expects($this->never())
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
        $this->clientStub->expects($this->never())
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
        $this->clientStub->expects($this->never())
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
        $this->clientStub->expects($this->once())
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
        $zendDbSelectStub = $this->getMockBuilder('\Zend_Db_Select')
            ->disableOriginalConstructor()
            ->getMock();
        $zendDbSelectStub->method('from')->willReturnSelf();
        $zendDbSelectStub->method('where')->willReturnSelf();
        $zendDbSelectStub->method('order')->willReturnSelf();

        $this->clientStub
            ->expects($this->exactly(2))
            ->method('select')
            ->willReturn($zendDbSelectStub);

        $this->clientStub
            ->expects($this->once())
            ->method('fetchAll')
            ->willReturn([['data' => 1]]);

        $this->clientStub
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
        $this->clientStub->expects($this->once())
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
            ->willReturn($this->clientStub);

        return $clientFactoryStub;
    }

    private function getMockForMappings()
    {
        $mappingStub = $this->getMockBuilder('\G4\DataMapper\Common\MappingInterface')
            ->getMock();
        return $mappingStub;
    }
}