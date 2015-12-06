<?php

class MySQLClientFactoryTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var array
     */
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


    }

    protected function tearDown()
    {

    }

    public function testCreate()
    {

    }
}