<?php

use G4\DataMapper\Engine\Solr\SolrCollectionName;

class SolrCollectionNameTest extends PHPUnit_Framework_TestCase
{

    private $solrCollectionName;

    private $collectionName;

    protected function setUp()
    {
        $this->collectionName     = 'profiles';
        $this->solrCollectionName = new SolrCollectionName($this->collectionName);
    }

    protected function tearDown()
    {
        $this->solrCollectionName = null;
        $this->collectionName     = null;
    }

    public function testToString()
    {
        $this->assertEquals($this->collectionName, (string) $this->solrCollectionName);
    }

    public function testCollectionNameException()
    {
        $this->expectException('\G4\DataMapper\Exception\CollectionNameException');

        new SolrCollectionName(null);

        $this->expectException('\G4\DataMapper\Exception\CollectionNameException');
        new SolrCollectionName('');

        $this->expectException('\G4\DataMapper\Exception\CollectionNameException');
        new SolrCollectionName(123);
    }

}