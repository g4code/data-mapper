<?php

use G4\DataMapper\Builder;
use G4\DataMapper\Engine\MySQL\MySQLAdapter;
use G4\DataMapper\Engine\MySQL\MySQLClientFactory;
use G4\DataMapper\Common\Identity;

class InsertTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Builder
     */
    private $builder;

    /**
     * @var array
     */
    private $data;

    protected function setUp()
    {
        $this->data = [
            'id'      => '12345',
            'title'   => 'This is a sample text title',
            'content' => 'Lorem ipsum dolor sit amet, dolores noluisse iracundia qui an.',
        ];

        $params = [
            'host'     => '192.168.32.11',
            'port'     => 3306,
            'username' => 'root',
            'password' => 'root',
            'dbname'   => 'data_mapper',
        ];

        $clientFactory = new MySQLClientFactory($params);

        $adapter = new MySQLAdapter($clientFactory);

        $this->builder = Builder::create()
            ->adapter($adapter);
    }

    protected function tearDown()
    {
        $this->builder = null;
    }

    public function testInsert()
    {
        $this->builder
            ->type('test_insert')
            ->build()
            ->insert($this->makeMapping());

        $identity = new Identity();
        $identity
            ->field('id')
            ->equal(12345);

        $rawData = $this->builder
            ->type('test_insert')
            ->build()
            ->find($identity);

        $this->assertEquals(1, $rawData->count());
        $this->assertEquals($this->data, $rawData->getOne());
    }

    public function testExceptionOnInsert()
    {
        $this->expectException('\Exception');
        $this->expectExceptionCode(101);
        $this->expectExceptionMessage(
            "SQLSTATE[42S02]: Base table or view not found: 1146 Table 'data_mapper.test_insert_fail' doesn't exist, query was: INSERT INTO `test_insert_fail` (`id`, `title`, `content`) VALUES (?, ?, ?)"
        );
        $this->builder
            ->type('test_insert_fail')
            ->build()
            ->insert($this->makeMapping());
    }

    private function makeMapping()
    {
        $mapping = $this->getMockBuilder('\G4\DataMapper\Common\MappingInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $mapping
            ->expects($this->once())
            ->method('map')
            ->willReturn($this->data);

        return $mapping;
    }
}