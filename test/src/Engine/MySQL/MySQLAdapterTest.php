<?php

use G4\DataMapper\Engine\MySQL\MySQLAdapter;

class MySQLAdapterTest extends PHPUnit_Framework_TestCase
{

    private $adapter;

    private $params;


    protected function setUp()
    {
        $this->params = [
            'host'     => '127.0.0.1',
            'port'     => '3306',
            'username' => 'test',
            'password' => 'test',
            'dbname'   => 'data_mapper',
        ];
        $this->adapter = new MySQLAdapter($this->getMock('\G4\DataMapper\Engine\MySQL\MySQLClientFactory', null, [$this->params]));
    }

    protected function tearDown()
    {
        $this->params = [];
        $this->adapter = null;
    }

    public function testInsert()
    {

    }
}