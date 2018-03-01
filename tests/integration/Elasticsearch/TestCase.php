<?php

namespace G4\DataMapper\Test\Integration\Elasticsearch;

use G4\DataMapper\Common\Identity;
use G4\DataMapper\Engine\Elasticsearch\ElasticsearchAdapter;
use G4\DataMapper\Engine\Elasticsearch\ElasticsearchClientFactory;
use G4\DataMapper\Builder;
use G4\DataMapper\Engine\Elasticsearch\ElasticsearchCollectionName;

class TestCase extends \PHPUnit_Framework_TestCase
{
    private $clientFactory;

    private $insertData;

    protected function setUp()
    {
        $this->clientFactory = new ElasticsearchClientFactory(['host' => '192.168.99.99', 'port' => '9200']);
    }

    public function testClientFactory()
    {
        $adapter = new ElasticsearchAdapter($this->clientFactory);

        $builder = Builder::create()->adapter($adapter);

        $mapper = $builder->collectionName(new ElasticsearchCollectionName('user'))->buildMapper();

        $identity = new Identity();

//        $identity->field('gender')->equal('female')->sortAscending('id')->setFieldNames(['first_name', 'last_name']);
//
//        $mapper->find($identity);

        //end of integration test for find

//        $this->insertData = [
//                'id'         => '667',
//                'first_name' => 'Devil',
//                'last_name'  => 'Himself',
//                'gender'     => 'female',
//
//        ];
//
//        $mapper->insert($this->makeMapping());

        //end of integration test for insert

        $identity = new Identity();

        $identity->field('id')->equal('666');

        $this->insertData = [
            'id'         => '667',
            'first_name' => 'Devil updated',
            'last_name'  => 'Himself',
            'gender'     => 'unknown',
        ];

        $mapper->update($this->makeMapping(), $identity);

        //end of integration test for update

//        $identity = new Identity();
//
//        $identity->field('id')->equal('666');
//
//        $mapper->delete($identity);

        //end of integration test for delete

        var_dump('kraj proto integracionog testa');
        die;
    }

    public function makeMapping()
    {
        $mapping = $this->getMockBuilder(\G4\DataMapper\Common\MappingInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mapping
            ->expects($this->any())
            ->method('map')
            ->willReturn($this->insertData);

        return $mapping;
    }
}
