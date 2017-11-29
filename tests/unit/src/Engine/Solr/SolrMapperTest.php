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

    public function testFind()
    {
        $rawDataStub = $this->getMockBuilder('\G4\DataMapper\Common\RawData')
            ->disableOriginalConstructor()
            ->getMock();

        $this->adapterMock
            ->expects($this->once())
            ->method('select')
            ->willReturn($rawDataStub);

        $this->assertSame($rawDataStub, $this->mapper->find($this->getMock('\G4\DataMapper\Common\Identity')));
    }

    public function testFindException()
    {
        $this->adapterMock
            ->expects($this->once())
            ->method('select')
            ->will($this->throwException(new \Exception()));

        $this->expectException('\Exception');

        $this->mapper->find($this->getMock('\G4\DataMapper\Common\Identity'));
    }

    public function testInsert()
    {
        $this->adapterMock
            ->expects($this->once())
            ->method('insert')
            ->with($this->equalTo($this->collectionNameMock), $this->equalTo($this->mappingMock));

        $this->mapper->insert($this->mappingMock);
    }

    public function testInsertException()
    {
        $this->adapterMock
            ->expects($this->once())
            ->method('insert')
            ->with($this->equalTo($this->collectionNameMock), $this->equalTo($this->mappingMock))
            ->will($this->throwException(new \Exception()));

        $this->expectException('\Exception');

        $this->mapper->insert($this->mappingMock);
    }

    public function testUpdate()
    {
        $this->adapterMock
            ->expects($this->once())
            ->method('update')
            ->with($this->equalTo($this->collectionNameMock), $this->equalTo($this->mappingMock));

        $this->mapper->update($this->mappingMock, $this->getMock('\G4\DataMapper\Common\Identity'));
    }

    public function testUpdateException()
    {
        $this->adapterMock
            ->expects($this->once())
            ->method('update')
            ->with($this->equalTo($this->collectionNameMock), $this->equalTo($this->mappingMock))
            ->will($this->throwException(new \Exception()));

        $this->expectException('\Exception');

        $this->mapper->update($this->mappingMock, $this->getMock('\G4\DataMapper\Common\Identity'));
    }

    public function testUpsert()
    {
        $this->adapterMock
            ->expects($this->once())
            ->method('upsert')
            ->with($this->equalTo($this->collectionNameMock), $this->equalTo($this->mappingMock));

        $this->mapper->upsert($this->mappingMock);
    }

    public function testUpsertException()
    {
        $this->adapterMock
            ->expects($this->once())
            ->method('upsert')
            ->will($this->throwException(new \Exception()));

        $this->expectException('\Exception');

        $this->mapper->upsert($this->mappingMock);
    }
}