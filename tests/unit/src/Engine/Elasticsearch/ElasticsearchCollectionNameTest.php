<?php

use G4\DataMapper\Engine\Elasticsearch\ElasticsearchCollectionName;

class ElasticsearchCollectionNameTest extends \PHPUnit_Framework_TestCase
{
    private $elasticsearchCollectionName;

    private $collectionName;

    protected function setUp()
    {
        $this->collectionName     = 'profiles';
        $this->elasticsearchCollectionName = new ElasticsearchCollectionName($this->collectionName);
    }

    protected function tearDown()
    {
        $this->elasticsearchCollectionName = null;
        $this->collectionName     = null;
    }

    public function testToString()
    {
        $this->assertEquals($this->collectionName, (string) $this->elasticsearchCollectionName);
    }

    public function testCollectionNameException()
    {
        $this->expectException(\G4\DataMapper\Exception\CollectionNameException::class);

        new ElasticsearchCollectionName(null);

        $this->expectException(\G4\DataMapper\Exception\CollectionNameException::class);
        new ElasticsearchCollectionName('');

        $this->expectException(\G4\DataMapper\Exception\CollectionNameException::class);
        new ElasticsearchCollectionName(123);
    }
}
