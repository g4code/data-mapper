<?php

use G4\DataMapper\Engine\Elasticsearch\ElasticsearchTypeName;

class ElasticsearchTypeNameTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var ElasticsearchIndexAndType
     */
    private $elasticsearchTypeName;

    /**
     * @var string
     */
    private $typeName = 'profiles';


    protected function setUp()
    {
        $this->elasticsearchTypeName = new ElasticsearchTypeName($this->typeName);
    }

    protected function tearDown()
    {
        $this->elasticsearchTypeName = null;
    }

    public function testToString()
    {
        $this->assertEquals($this->typeName, (string) $this->elasticsearchTypeName);
    }

    public function testTypeNameException()
    {
        $this->expectException('\G4\DataMapper\Exception\TypeNameException');

        new ElasticsearchTypeName(null);

        $this->expectException('\G4\DataMapper\Exception\TypeNameException');
        new ElasticsearchTypeName('');

        $this->expectException('\G4\DataMapper\Exception\TypeNameException');
        new ElasticsearchTypeName(12334);
    }
}