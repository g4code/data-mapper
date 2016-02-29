<?php

use G4\DataMapper\Builder;

class BuilderTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var \G4\DataMapper\Builder
     */
    private $builder;

    /**
     * @var array
     */
    private $params;


    protected function setUp()
    {
        $this->params = [
            'host'     => 'localhost',
            'port'     => 3306,
            'username' => 'test_username',
            'password' => 'test_password',
            'dbname'   => 'test_dbname',
        ];
        $this->builder = Builder::create();
    }

    protected function tearDown()
    {
        $this->params = null;
        $this->builder = null;
    }

    public function testCreate()
    {
        $this->assertInstanceOf('\G4\DataMapper\Builder', $this->builder);
    }

    public function testEngineMySQL()
    {
        $mapper = $this->builder
            ->engineMySQL($this->params)
            ->table('profiles')
            ->build();
        $this->assertInstanceOf('\G4\DataMapper\Engine\MySQL\MySQLMapper', $mapper);
    }

    public function testBuild()
    {
        $this->builder
            ->table('profiles')
            ->adapter($this->getMockForMySQLAdapter());
        $this->builder->build();
    }

    public function testBuildWithNoAdapter()
    {
        $this->builder->table('profiles');
        $this->expectException('\Exception');
        $this->expectExceptionCode(601);
        $this->expectExceptionMessage('Adapter instance must implement AdapterInterface');
        $this->builder->build();
    }

    public function testBuildWithNoType()
    {
        $this->builder->adapter($this->getMock('\G4\DataMapper\Common\AdapterInterface'));
        $this->expectException('\Exception');
        $this->expectExceptionCode(601);
        $this->expectExceptionMessage('DataSet cannot be emty');
        $this->builder->build();
    }

    public function testBuildForUnknownEngine()
    {
        $this->builder
            ->adapter($this->getMock('\G4\DataMapper\Common\AdapterInterface'))
            ->table('profiles');
        $this->expectException('\Exception');
        $this->expectExceptionCode(601);
        $this->expectExceptionMessage('Unknown engine');
        $this->builder->build();
    }

    private function getMockForMySQLAdapter()
    {
        return $this->getMock(
            '\G4\DataMapper\Engine\MySQL\MySQLAdapter',
            null,
            [
                $this->getMock('\G4\DataMapper\Engine\MySQL\MySQLClientFactory', null, [$this->params]),
            ]
        );
    }
}