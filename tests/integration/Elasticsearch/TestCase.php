<?php

namespace G4\DataMapper\Test\Integration\Elasticsearch;

use G4\DataMapper\Builder;
use G4\DataMapper\Engine\Elasticsearch\ElasticsearchAdapter;
use G4\DataMapper\Engine\Elasticsearch\ElasticsearchClientFactory;
use G4\DataMapper\Engine\Elasticsearch\ElasticsearchCollectionName;
use G4\DataMapper\Engine\Elasticsearch\ElasticsearchIdentity;

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
        $this->id = '1';

        $this->data = [
            'id'         => $this->id,
            'first_name' => 'Test',
            'last_name'  => 'User',
            'status'     => '5',
        ];

        $params = [
            'host' => '192.168.32.11',
            'port' => '9200',
        ];

        $clientFactory = new ElasticsearchClientFactory($params);

        $adapter = new ElasticsearchAdapter($clientFactory);

        $this->builder = Builder::create()->adapter($adapter);
    }

    protected function tearDown()
    {
        $this->builder = null;
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
            ->collectionName(new ElasticsearchCollectionName($this->getCollectionName()))
            ->buildMapper();
    }

    public function makeMapping()
    {
        $mapping = $this->getMockBuilder(\G4\DataMapper\Common\MappingInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mapping
            ->expects($this->any())
            ->method('map')
            ->willReturn($this->getData());

        return $mapping;
    }

    public function makeIdentityById()
    {
        $identity = new ElasticsearchIdentity();
        $identity
            ->field('id')
            ->equal($this->getId());

        return $identity;
    }
}
