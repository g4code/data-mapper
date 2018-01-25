<?php

namespace G4\DataMapper\Test\Integration\Solr;

use G4\DataMapper\Builder;
use G4\DataMapper\Engine\Solr\SolrAdapter;
use G4\DataMapper\Engine\Solr\SolrClientFactory;
use G4\DataMapper\Engine\Solr\SolrCollectionName;
use G4\DataMapper\Engine\Solr\SolrIdentity;

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
        $this->id = 1;

        $this->data = [
            'id'         => $this->id,
            'first_name' => 'Test',
            'last_name'  => 'User',
            'status'     => 5,
        ];


        $params = [
            'host' => '192.168.32.11',
            'port' => '8983',
        ];

        $clientFactory = new SolrClientFactory($params);

        $adapter = new SolrAdapter($clientFactory);

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
            ->collectionName(new SolrCollectionName($this->getCollectionName()))
            ->buildMapper();
    }

    public function makeMapping()
    {
        $mapping = $this->getMockBuilder(\G4\DataMapper\Common\MappingInterface::class)
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
        $identity = new SolrIdentity();
        $identity
            ->field('id')
            ->equal($this->getId());

        return $identity;
    }
}
