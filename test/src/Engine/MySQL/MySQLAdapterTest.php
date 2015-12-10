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
        $this->adapter->delete('data', ['id' => 1]);
    }

    public function testEmptyDataForDelete()
    {
        $this->clientStub->expects($this->never())
            ->method('delete');
        $this->setExpectedException('\Exception', 'Empty identifiers for delete');
        $this->adapter->delete('data', []);
    }

    public function testEmptyDataForInsert()
    {
        $this->clientStub->expects($this->never())
            ->method('insert');
        $this->setExpectedException('\Exception', 'Empty data for insert');
        $this->adapter->insert('data', []);
    }

    public function testInsert()
    {
        $this->clientStub->expects($this->once())
            ->method('insert');
        $this->adapter->insert('data', ['id' => 1]);
    }

    private function getMockForMySQLClientFactory()
    {
        $this->clientStub = $this->getMockBuilder('\Zend_Db_Adapter_Mysqli')
            ->disableOriginalConstructor()
            ->setMethods(['insert', 'delete'])
            ->getMock();

        $clientFactoryStub = $this->getMockBuilder('\G4\DataMapper\Engine\MySQL\MySQLClientFactory')
            ->disableOriginalConstructor()
            ->getMock();

        $clientFactoryStub->method('create')
            ->willReturn($this->clientStub);

        return $clientFactoryStub;
    }
}