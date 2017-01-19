<?php

use G4\DataMapper\Engine\Elasticsearch\ElasticsearchMapper;

class ElasticsearchMapperTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var ElasticsearchMapper
     */
    private $elasticsearchMapper;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    private $adapterMock;


    protected function setUp()
    {
        $this->adapterMock = $this->getMockBuilder('\G4\DataMapper\Common\AdapterInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $indexMock = $this->getMockBuilder('\G4\DataMapper\Engine\Elasticsearch\ElasticsearchIndexName')
            ->disableOriginalConstructor()
            ->getMock();

        $typeMock = $this->getMockBuilder('\G4\DataMapper\Engine\Elasticsearch\ElasticsearchTypeName')
            ->disableOriginalConstructor()
            ->getMock();

        $this->elasticsearchMapper = new ElasticsearchMapper($this->adapterMock, $indexMock, $typeMock);
    }

    protected function tearDown()
    {
        $this->adapterMock          = null;
        $this->elasticsearchMapper  = null;
    }

    public function testDelete()
    {
        $identityStub = $this->getMock('\G4\DataMapper\Common\IdentityInterface');

        $this->adapterMock
            ->expects($this->once())
            ->method('delete')
            ->with(
                $this->isInstanceOf('\G4\DataMapper\Engine\Elasticsearch\ElasticsearchCollectionName'),
                $this->isInstanceOf('\G4\DataMapper\Engine\Elasticsearch\ElasticsearchSelectionFactory')
            );

        $this->elasticsearchMapper->delete($identityStub);

    }
}