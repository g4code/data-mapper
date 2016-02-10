<?php

namespace G4\DataMapper\Test\Integration\MySQL;

use G4\DataMapper\Builder;
use G4\DataMapper\Engine\MySQL\MySQLAdapter;
use G4\DataMapper\Engine\MySQL\MySQLClientFactory;
use G4\DataMapper\Common\Identity;

class TestCase extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Builder
     */
    private $builder;

    /**
     * @var array
     */
    private $data;

    /**
     * @var int
     */
    private $id;

    protected function setUp()
    {
        $this->id = 12345;

        $this->data = [
            'id'      => $this->getId(),
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

    public function testExceptionOnInsert()
    {
        $this->expectException('\Exception');
        $this->expectExceptionCode(101);
        $this->expectExceptionMessageRegExp('~^42\:\sSQLSTATE\[.*$~xius');

        $this->getBuilder()
            ->type($this->getTableName() . '_fail')
            ->build()
            ->insert($this->makeMapping());
    }

    public function getBuilder()
    {
        return $this->builder;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getId()
    {
        return $this->id;
    }

    public function makeMapper()
    {
        return $this->getBuilder()
            ->type($this->getTableName())
            ->build();
    }

    public function makeMapping()
    {
        $mapping = $this->getMockBuilder('\G4\DataMapper\Common\MappingInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $mapping
            ->expects($this->once())
            ->method('map')
            ->willReturn($this->getData());

        return $mapping;
    }

    public function makeIdentityById()
    {
        $identity = new Identity();
        $identity
            ->field('id')
            ->equal($this->getId());

        return $identity;
    }
}