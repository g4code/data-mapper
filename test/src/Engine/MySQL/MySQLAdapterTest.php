<?php

use G4\DataMapper\Engine\MySQL\MySQLAdapter;

class MySQLAdapterTest extends PHPUnit_Framework_TestCase
{

    private $adapter;


    protected function setUp()
    {
        $this->adapter = new MySQLAdapter($this->getMockForMySQLClientFactory());
    }

    protected function tearDown()
    {
        $this->params = [];
        $this->adapter = null;
    }

    public function testEmptyDataForInsert()
    {
        $this->setExpectedException('\Exception', 'Empty data for insert');
        $this->adapter->insert('data', []);
    }

    public function testInsert()
    {
        $this->adapter->insert('data', ['id' => 1]);
    }

    private function getMockForMySQLClientFactory()
    {
        $clientMock = $this->getMockBuilder('\Zend_Db_Adapter_Abstract')
            ->disableOriginalConstructor()
            ->getMock();

        $clientMock->expects($this->once())
            ->method('insert')
            ->willReturn(true);

        $clientFactoryMock = $this->getMockBuilder('\G4\DataMapper\Engine\MySQL\MySQLClientFactory')
            ->disableOriginalConstructor()
            ->getMock();

        $clientFactoryMock->method('create')
            ->willReturn($clientMock);

        return $clientFactoryMock;
    }
}