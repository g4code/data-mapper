<?php

use G4\DataMapper\Builder;
use G4\DataMapper\Engine\MySQL\MySQLAdapter;
use G4\DataMapper\Engine\MySQL\MySQLClientFactory;
use G4\DataMapper\Engine\MySQL\MySQLMapper;

class InsertTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Builder
     */
    private $builder;

    protected function setUp()
    {
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
    }

    public function testExceptionOnInsert()
    {
        $this->setExpectedException(
            '\Exception',
            "SQLSTATE[42S02]: Base table or view not found: 1146 Table 'data_mapper.test_insert_fail' doesn't exist, query was: INSERT INTO `test_insert_fail` (`id`, `title`, `content`) VALUES (?, ?, ?)" ,
            101
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
            ->willReturn([
                'id'      => '12345',
                'title'   => 'This is a sample text title',
                'content' => 'Lorem ipsum dolor sit amet, dolores noluisse iracundia qui an.
                            Nam facilisi urbanitas no, mei ut paulo lobortis.
                            Sed tota nominati omittantur et, graeci semper usu cu, te cum aperiri scripserit.
                            Sit cu tale solum.
                            Vero adipiscing vim in, vel adhuc justo ea, quo quis praesent urbanitas no.
                            Eros argumentum an mel, utroque reprimique ea nec',
            ]);

        return $mapping;
    }
}