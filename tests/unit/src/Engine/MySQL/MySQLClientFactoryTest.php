<?php

use G4\DataMapper\Engine\MySQL\MySQLClientFactory;

class MySQLClientFactoryTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var array
     */
    private $params;

    /**
     * @var MySQLClientFactory
     */
    private $clientFactory;


    protected function setUp()
    {
        $this->params = [
            'host'     => '127.0.0.1',
            'port'     => '3306',
            'username' => 'test',
            'password' => 'test',
            'dbname'   => 'data_mapper',
        ];

        $this->clientFactory = new MySQLClientFactory($this->params);

    }

    protected function tearDown()
    {
        $this->params = null;
        $this->clientFactory = null;
    }

    public function testCreate()
    {
        $this->assertInstanceOf(\Zend_Db_Adapter_Abstract::class, $this->clientFactory->create());
    }

    public function testParamsWithNoDbname()
    {
        $this->paramsTest('dbname', 'No dbname param');
    }

    public function testParamsWithNoHost()
    {
        $this->paramsTest('host', 'No host param');
    }

    public function testParamsWithNoPassword()
    {
        $this->paramsTest('password', 'No password param');
    }

    public function testParamsWithNullPassword()
    {
        $this->params['password'] = null;
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('No password param');
        new MySQLClientFactory($this->params);
    }

    public function testEmptyPassword()
    {
        $this->params['password'] = '';
        $clientFactory = new MySQLClientFactory($this->params);
        $this->assertInstanceOf(\Zend_Db_Adapter_Abstract::class, $clientFactory->create());
    }

    public function testParamsWithNoPort()
    {
        $this->paramsTest('port', 'No port param');
    }

    public function testParamsWithNoUsername()
    {
        $this->paramsTest('username', 'No username param');
    }

    private function paramsTest($key, $message)
    {
        unset($this->params[$key]);
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage($message);
        new MySQLClientFactory($this->params);
    }
}
