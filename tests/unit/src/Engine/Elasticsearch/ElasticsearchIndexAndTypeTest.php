<?php

use G4\DataMapper\Engine\Elasticsearch\ElasticsearchIndexAndType;

class ElasticsearchIndexAndTypeTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var ElasticsearchIndexAndType
     */
    private $elasticsearchIndexAndType;

    /**
     * @var string
     */
    private $indexName = 'users';

    /**
     * @var string
     */
    private $typeName = 'profiles';


    protected function setUp()
    {
        $this->elasticsearchIndexAndType = new ElasticsearchIndexAndType($this->indexName, $this->typeName);
    }

    protected function tearDown()
    {
        $this->elasticsearchIndexAndType = null;
    }

    public function testToString()
    {
        $this->assertEquals($this->indexName, (string) $this->elasticsearchIndexAndType);
    }

    public function testGetIndexName()
    {
        $this->assertEquals($this->indexName, $this->elasticsearchIndexAndType->getIndexName());
    }

    public function testGetTypeName()
    {
        $this->assertEquals($this->typeName, $this->elasticsearchIndexAndType->getTypeName());
    }

    public function testIndexNameException()
    {
        $this->expectException('\G4\DataMapper\Exception\IndexNameException');

        new ElasticsearchIndexAndType(null, $this->typeName);

        $this->expectException('\G4\DataMapper\Exception\IndexNameException');
        new ElasticsearchIndexAndType('', $this->typeName);

        $this->expectException('\G4\DataMapper\Exception\IndexNameException');
        new ElasticsearchIndexAndType(12345, $this->typeName);
    }

    public function testTypeNameException()
    {
        $this->expectException('\G4\DataMapper\Exception\TypeNameException');

        new ElasticsearchIndexAndType($this->indexName, null);

        $this->expectException('\G4\DataMapper\Exception\TypeNameException');
        new ElasticsearchIndexAndType($this->indexName, '');

        $this->expectException('\G4\DataMapper\Exception\TypeNameException');
        new ElasticsearchIndexAndType($this->indexName, 12334);
    }
}