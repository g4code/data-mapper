<?php

use G4\DataMapper\Engine\Elasticsearch\ElasticsearchIndexName;

class ElasticsearchIndexNameTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var ElasticsearchIndexAndType
     */
    private $elasticsearchIndexName;

    /**
     * @var string
     */
    private $indexName = 'users';


    protected function setUp()
    {
        $this->elasticsearchIndexName = new ElasticsearchIndexName($this->indexName);
    }

    protected function tearDown()
    {
        $this->elasticsearchIndexName = null;
    }

    public function testToString()
    {
        $this->assertEquals($this->indexName, (string) $this->elasticsearchIndexName);
    }

    public function testIndexNameException()
    {
        $this->expectException('\G4\DataMapper\Exception\IndexNameException');

        new ElasticsearchIndexName(null);

        $this->expectException('\G4\DataMapper\Exception\IndexNameException');
        new ElasticsearchIndexName('');

        $this->expectException('\G4\DataMapper\Exception\IndexNameException');
        new ElasticsearchIndexName(12345);
    }

}