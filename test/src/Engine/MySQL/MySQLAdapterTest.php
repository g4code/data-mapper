<?php

use G4\DataMapper\Engine\MySQL\MySQLAdapter;

class MySQLAdapterTest extends PHPUnit_Framework_TestCase
{

    private $adapter;

    private $clientStub;


    protected function setUp()
    {
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
        $this->clientStub = $this->getMockBuilder('\Zend_Db_Adapter_Mysqli')
            ->disableOriginalConstructor()
            ->setMethods(['insert', 'delete', 'update'])
            ->getMock();

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