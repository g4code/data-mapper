<?php

use G4\DataMapper\Engine\Solr\SolrMapper;

class SolrMapperTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $adapterMock;

    /**
     * @var SolrMapper
     */
    private $mapper;

    private $mappingMock;

    private $collectionNameMock;

    protected function setUp()
    {
        $this->adapterMock = $this->getMockBuilder('\G4\DataMapper\Engine\Solr\SolrAdapter')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mappingMock = $this->getMockBuilder('\G4\DataMapper\Common\MappingInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->collectionNameMock = $this->getMockBuilder('\G4\DataMapper\Engine\Solr\SolrCollectionName')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mapper = new SolrMapper($this->collectionNameMock, $this->adapterMock);
    }

    protected function tearDown()
    {
        $this->adapterMock        = null;
        $this->mapper             = null;
        $this->mappingMock        = null;
        $this->collectionNameMock = null;
    }

    public function testDelete()
    {
        $identityStub = $this->getMock('\G4\DataMapper\Common\IdentityInterface');

        $this->adapterMock
            ->expects($this->once())
            ->method('delete');

        $this->mapper->delete($identityStub);
    }

    public function testDeleteException()
    {
        $identityStub = $this->getMock('\G4\DataMapper\Common\IdentityInterface');

        $this->adapterMock
            ->expects($this->once())
            ->method('delete')
            ->will($this->throwException(new \Exception()));

        $this->expectException('\Exception');

        $this->mapper->delete($identityStub);
    }
}