<?php

use G4\DataMapper\Engine\Solr\SolrMapper;
use G4\DataMapper\Exception\SolrMapperException;

class SolrMapperTest extends PHPUnit_Framework_TestCase
{
    const SOLR_DATA_MAPPER_ERROR_MESSAGE = 'Solr Mapper error';

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $adapterMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $collectionNameMock;

    /**
     * @var SolrMapper
     */
    private $mapper;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $mappingMock;

    protected function setUp()
    {
        $this->adapterMock = $this->getMockBuilder(\G4\DataMapper\Engine\Solr\SolrAdapter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mappingMock = $this->getMockBuilder(\G4\DataMapper\Common\MappingInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->collectionNameMock = $this->getMockBuilder(\G4\DataMapper\Engine\Solr\SolrCollectionName::class)
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
        $identityStub = $this->getMock(\G4\DataMapper\Common\IdentityInterface::class);

        $this->adapterMock
            ->expects($this->once())
            ->method('delete');

        $this->mapper->delete($identityStub);
    }

    public function testDeleteException()
    {
        $identityStub = $this->getMock(\G4\DataMapper\Common\IdentityInterface::class);

        $this->adapterMock
            ->expects($this->once())
            ->method('delete')
            ->will($this->throwException(new \Exception()));

        $this->expectException(\Exception::class);

        $this->mapper->delete($identityStub);
    }

    public function testFind()
    {
        $rawDataStub = $this->getMockBuilder(\G4\DataMapper\Common\RawData::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->adapterMock
            ->expects($this->once())
            ->method('select')
            ->willReturn($rawDataStub);

        $this->assertSame($rawDataStub, $this->mapper->find($this->getMock(\G4\DataMapper\Common\Identity::class)));
    }

    public function testFindException()
    {
        $this->adapterMock
            ->expects($this->once())
            ->method('select')
            ->will($this->throwException(new SolrMapperException(self::SOLR_DATA_MAPPER_ERROR_MESSAGE)));

        $this->expectException(SolrMapperException::class);

        $this->mapper->find($this->getMock(\G4\DataMapper\Common\Identity::class));
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
            ->will($this->throwException(new SolrMapperException(self::SOLR_DATA_MAPPER_ERROR_MESSAGE)));

        $this->expectException(SolrMapperException::class);

        $this->mapper->insert($this->mappingMock);
    }

    public function testUpdate()
    {
        $this->adapterMock
            ->expects($this->once())
            ->method('update')
            ->with($this->equalTo($this->collectionNameMock), $this->equalTo($this->mappingMock));

        $this->mapper->update($this->mappingMock, $this->getMock(\G4\DataMapper\Common\Identity::class));
    }

    public function testUpdateException()
    {
        $this->adapterMock
            ->expects($this->once())
            ->method('update')
            ->with($this->equalTo($this->collectionNameMock), $this->equalTo($this->mappingMock))
            ->will($this->throwException(new SolrMapperException(self::SOLR_DATA_MAPPER_ERROR_MESSAGE)));

        $this->expectException(SolrMapperException::class);

        $this->mapper->update($this->mappingMock, $this->getMock(\G4\DataMapper\Common\Identity::class));
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
            ->will($this->throwException(new SolrMapperException(self::SOLR_DATA_MAPPER_ERROR_MESSAGE)));

        $this->expectException(SolrMapperException::class);

        $this->mapper->upsert($this->mappingMock);
    }

    public function testQuery()
    {
        $this->adapterMock
            ->expects($this->once())
            ->method('query')
            ->willReturn(true);

        $this->assertTrue($this->mapper->query('query solr'));
    }

    public function testQueryException()
    {
        $this->adapterMock
            ->expects($this->once())
            ->method('query')
            ->willThrowException(new SolrMapperException(self::SOLR_DATA_MAPPER_ERROR_MESSAGE));

        $this->expectException(SolrMapperException::class);

        $this->mapper->query('solr');
    }
}
