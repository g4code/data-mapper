<?php

use G4\DataMapper\Engine\Solr\SolrAdapter;

class SolrAdapterTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var SolrAdapter
     */
    private $adapter;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $clientMock;

    private $collectionNameMock;

    protected function setUp()
    {
        $this->clientMock = $this->getMockBuilder('\G4\DataMapper\Engine\Solr\SolrClient')
            ->disableOriginalConstructor()
            ->getMock();

        $this->collectionNameMock = $this->getMockBuilder('\G4\DataMapper\Engine\Solr\SolrCollectionName')
            ->disableOriginalConstructor()
            ->getMock();

        $this->collectionNameMock
            ->expects($this->any())
            ->method('__toString')
            ->willReturn('nd_api');


        $this->adapter = new SolrAdapter($this->getMockForSolrClientFactory());
    }

    protected function tearDown()
    {
        $this->adapter = null;
        $this->clientMock = null;
        $this->collectionNameMock = null;
    }

    private function getMockForSolrClientFactory()
    {
        $clientFactoryStub = $this->getMockBuilder('\G4\DataMapper\Engine\Solr\SolrClientFactory')
            ->disableOriginalConstructor()
            ->getMock();

        $clientFactoryStub->method('create')
            ->willReturn($this->clientMock);

        return $clientFactoryStub;
    }
}
