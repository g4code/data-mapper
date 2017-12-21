<?php

namespace G4\DataMapper\Test\Integration\Solr;

use G4\DataMapper\Builder;
use G4\DataMapper\Engine\Solr\SolrAdapter;
use G4\DataMapper\Engine\Solr\SolrClientFactory;
use G4\DataMapper\Engine\Solr\SolrCollectionName;
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
        $this->id = 5555;

        $this->data = [
            'id'=> '5555',
            'message_id'=>22,
            'type'=>1,
            'message'=>'Lorem Ipsum Dolor Amet Sit 333',
            'media_uuid'=>'',
            'direction'=>2,
            'read'=>0,
            'deleted'=>0,
            'ts_created'=>1507712599,
            'user_id'=>16454,
            'user_status'=>1,
            'user_level'=>1,
            'user_type'=>2,
            'user_country_id'=>228,
            'user_site_id'=>1,
            'related_user_id'=>15506,
            'related_user_status'=>1,
            'related_user_level'=>0,
            'related_user_type'=>2,
            'related_user_country_id'=>228,
            'related_user_site_id'=>1,
            'group_user_and_related'=>'16454|15506'
        ];


        $params = [
            'host'     => '192.168.99.99',
            'port'     => '8983',
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
        $identity = new Identity();
        $identity
            ->field('id')
            ->equal($this->getId());

        return $identity;
    }
}
