<?php

use G4\DataMapper\Engine\Elasticsearch\ElasticsearchCollectionName;

class ElasticsearchCollectionNameTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var ElasticsearchCollectionName
     */
    private $collectionName;

    private $indexNameMock;

    private $typeNameMock;


    protected function setUp()
    {
        $this->indexNameMock = $this->getMockBuilder('\G4\DataMapper\Engine\Elasticsearch\ElasticsearchIndexName')
            ->disableOriginalConstructor()
            ->getMock();

        $this->typeNameMock = $this->getMockBuilder('\G4\DataMapper\Engine\Elasticsearch\ElasticsearchTypeName')
            ->disableOriginalConstructor()
            ->getMock();

        $this->collectionName = new ElasticsearchCollectionName($this->indexNameMock, $this->typeNameMock);
    }

    protected function tearDown()
    {
        $this->indexNameMock    = null;
        $this->typeNameMock     = null;
        $this->collectionName   = null;
    }

    public function testToString()
    {
        $this->assertEquals('', (string) $this->collectionName);
    }

    public function testGetIndexName()
    {
        $this->assertEquals($this->indexNameMock, $this->collectionName->getIndexName());
    }

    public function testGetTypeName()
    {
        $this->assertEquals($this->typeNameMock, $this->collectionName->getTypeName());
    }
}