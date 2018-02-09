<?php

use G4\DataMapper\Engine\Solr\SolrAdapter;
use G4\DataMapper\Exception\EmptyDataException;
use G4\DataMapper\ErrorCodes as ErrorCode;

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
        $this->clientMock = $this->getMockBuilder(\G4\DataMapper\Engine\Solr\SolrClient::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->collectionNameMock = $this->getMockBuilder(\G4\DataMapper\Engine\Solr\SolrCollectionName::class)
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

    public function testDelete()
    {
        $selectionFactoryStub = $this->getMockBuilder(\G4\DataMapper\Engine\Solr\SolrSelectionFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $selectionFactoryStub
            ->expects($this->once())
            ->method('where')
            ->willReturn('id:15500');

        $this->clientMock
            ->expects($this->once())
            ->method('setCollection')
            ->with($this->equalTo((string) $this->collectionNameMock))
            ->willReturnSelf();

        $this->clientMock
            ->expects($this->once())
            ->method('setDocument')
            ->with($this->equalTo(['delete' => ['query' => 'id:15500']]))
            ->willReturnSelf();

        $this->adapter->delete($this->collectionNameMock, $selectionFactoryStub);
    }

    public function insert()
    {
        $mappingMock = $this->getMappingMock();

        $mappingMock
            ->expects($this->once())
            ->method('map')
            ->willReturn(['id' => 1, 'first_name' => 'Uncle', 'last_name' => 'Bob', 'gender' => 'm']);

        $mappingMock
            ->expects($this->once())
            ->method('setCollection')
            ->with($this->equalTo((string) $this->collectionNameMock))
            ->willReturnSelf();

        $mappingMock
            ->expects($this->once())
            ->method('setDocument')
            ->with($this->equalTo(
                [
                    ['first_name' => ['add' => 'Uncle']],
                    ['last_name'  => ['add' => 'Bob']],
                    ['gender'     => ['add' => 'm']]
                ])
            )
            ->willReturnSelf();

        $this->clientMock
            ->expects($this->once())
            ->method('update');

        $this->adapter->insert($this->collectionNameMock, $mappingMock);
    }

    public function testInsertWithEmptyData()
    {
        $this->clientMock->expects($this->never())
            ->method('update');

        $mappingMock = $this->getMappingMock();

        $mappingMock
            ->expects($this->once())
            ->method('map')
            ->willReturn([]);

        $this->expectException(EmptyDataException::class);
        $this->expectExceptionMessage('Empty data for insert.');
        $this->expectExceptionCode(ErrorCode::EMPTY_DATA);

        $this->adapter->insert($this->collectionNameMock, $mappingMock);
    }

    public function testSelect()
    {
        $data = [
                    ['id' => '1', 'first_name' => 'test', 'last_name' => 'user', 'gender' => 'f'],
                    ['id' => '2', 'first_name' => 'test2', 'last_name' => 'user2', 'gender' => 'm'],
                ];

        $selectionFactoryStub = $this->getMockBuilder(\G4\DataMapper\Engine\Solr\SolrSelectionFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $selectionFactoryStub
            ->expects($this->once())
            ->method('where')
            ->willReturn('city_name:Belgrade');

        $selectionFactoryStub
            ->expects($this->once())
            ->method('fieldNames')
            ->willReturn('*');

        $selectionFactoryStub
            ->expects($this->once())
            ->method('limit')
            ->willReturn('2');

        $selectionFactoryStub
            ->expects($this->once())
            ->method('sort')
            ->willReturn('id desc');

        $selectionFactoryStub
            ->expects($this->once())
            ->method('offset')
            ->willReturn('0');

        $selectionFactoryStub
            ->expects($this->once())
            ->method('getGeodistParameters')
            ->willReturn([]);

        $this->clientMock
            ->expects($this->once())
            ->method('setCollection')
            ->with($this->equalTo((string) $this->collectionNameMock))
            ->willReturnSelf();

        $this->clientMock
            ->expects($this->once())
            ->method('setQuery')
            ->willReturnSelf();

        $this->clientMock
            ->expects($this->once())
            ->method('select')
            ->willReturn(['response' => [ 'docs' => $data ]]);

        $select = $this->adapter->select($this->collectionNameMock, $selectionFactoryStub);

        $rawData = new \G4\DataMapper\Common\RawData($data, 2);

        $this->assertInstanceOf(\G4\DataMapper\Common\RawData::class, $select);
        $this->assertEquals($rawData->count(), $select->count());
        $this->assertEquals($rawData->getAll(), $select->getAll());
    }

    public function testUpdate()
    {
        $mappingMock = $this->getMappingMock();

        $mappingMock
            ->expects($this->any())
            ->method('map')
            ->willReturn(['id' => 1, 'first_name' => 'Blaster', 'last_name' => 'Master']);

        $this->clientMock
            ->expects($this->once())
            ->method('setCollection')
            ->with($this->equalTo((string) $this->collectionNameMock))
            ->willReturnSelf();

        $this->clientMock
            ->expects($this->once())
            ->method('setDocument')
            ->with($this->equalTo([['id' => '1', 'first_name' => ['set' => 'Blaster'], 'last_name' => ['set' => 'Master']]]))
            ->willReturnSelf();

        $this->clientMock->expects($this->once())
            ->method('update');

        $selectionFactory = new \G4\DataMapper\Engine\Solr\SolrSelectionFactory(
            (new \G4\DataMapper\Engine\Solr\SolrIdentity())
                ->field('id')
                ->equal(1)
        );

        $this->adapter->update($this->collectionNameMock, $mappingMock, $selectionFactory);
    }

    public function testUpdateWithEmptyData()
    {
        $this->clientMock->expects($this->never())
            ->method('update');

        $mappingMock = $this->getMappingMock();

        $mappingMock
            ->expects($this->once())
            ->method('map')
            ->willReturn([]);

        $this->expectException(EmptyDataException::class);
        $this->expectExceptionMessage('Empty data for update');
        $this->expectExceptionCode(ErrorCode::EMPTY_DATA);

        $selectionFactory = new \G4\DataMapper\Engine\Solr\SolrSelectionFactory(
            (new \G4\DataMapper\Engine\Solr\SolrIdentity())
                ->field('id')
                ->equal(1)
        );

        $this->adapter->update($this->collectionNameMock, $mappingMock, $selectionFactory);
    }

    private function getMappingMock()
    {
        return $this->getMockBuilder(\G4\DataMapper\Common\MappingInterface::class)->getMock();
    }

    private function getMockForSolrClientFactory()
    {
        $clientFactoryStub = $this->getMockBuilder(\G4\DataMapper\Engine\Solr\SolrClientFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $clientFactoryStub->method('create')
            ->willReturn($this->clientMock);

        return $clientFactoryStub;
    }
}
