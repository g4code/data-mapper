<?php

use G4\DataMapper\Engine\Solr\SolrCollectionName;

class SolrCollectionNameTest extends \PHPUnit\Framework\TestCase
{

    private $solrCollectionName;

    private $collectionName;

    protected function setUp(): void
    {
        $this->collectionName     = 'profiles';
        $this->solrCollectionName = new SolrCollectionName($this->collectionName);
    }

    protected function tearDown(): void
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
        $this->expectException(\G4\DataMapper\Exception\CollectionNameException::class);

        new SolrCollectionName(null);

        $this->expectException(\G4\DataMapper\Exception\CollectionNameException::class);
        new SolrCollectionName('');

        $this->expectException(\G4\DataMapper\Exception\CollectionNameException::class);
        new SolrCollectionName(123);
    }

}
