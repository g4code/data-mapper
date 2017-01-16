<?php

use G4\DataMapper\Builder;
use G4\DataMapper\Engine\MySQL\MySQLTableName;

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
            ->collectionName(new MySQLTableName('profiles'))
            ->buildMapper();
        $this->assertInstanceOf('\G4\DataMapper\Engine\MySQL\MySQLMapper', $mapper);
    }

    public function testBuildMapper()
    {
        $this->builder
            ->collectionName(new MySQLTableName('profiles'))
            ->adapter($this->getMockForMySQLAdapter());
        $this->builder->buildMapper();
    }

    public function testBuildBulk()
    {
        $this->builder
            ->collectionName(new MySQLTableName('profiles'))
            ->adapter($this->getMockForMySQLAdapter());
        $this->assertInstanceOf('\G4\DataMapper\Common\Bulk', $this->builder->buildBulk());
    }

    public function testBuildWithNoAdapter()
    {
        $this->builder->collectionName(new MySQLTableName('profiles'));
        $this->expectException('\Exception');
        $this->expectExceptionCode(601);
        $this->expectExceptionMessage('Adapter instance must implement AdapterInterface');
        $this->builder->buildMapper();
    }

    public function testBuildWithNoType()
    {
        $this->builder->adapter($this->getMock('\G4\DataMapper\Common\AdapterInterface'));
        $this->expectException('\Exception');
        $this->expectExceptionCode(601);
        $this->expectExceptionMessage('DataSet cannot be emty');
        $this->builder->buildMapper();
    }

    public function testBuildForUnknownEngine()
    {
        $this->builder
            ->adapter($this->getMock('\G4\DataMapper\Common\AdapterInterface'))
            ->collectionName(new MySQLTableName('profiles'));
        $this->expectException('\Exception');
        $this->expectExceptionCode(601);
        $this->expectExceptionMessage('Unknown engine');
        $this->builder->buildMapper();
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