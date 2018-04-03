<?php

use G4\DataMapper\Engine\Elasticsearch\ElasticsearchAdapter;
use G4\DataMapper\Exception\EmptyDataException;
use G4\DataMapper\ErrorCodes as ErrorCode;
use G4\DataMapper\Exception\NotImplementedException;

class ElasticsearchAdapterTest extends PHPUnit_Framework_TestCase
{

    const METHOD_POST   = 'POST';
    const METHOD_PUT    = 'PUT';
    const METHOD_DELETE = 'DELETE';

    /**
     * @var ElasticsearchAdapter
     */
    private $adapter;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $clientMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $collectionNameMock;

    protected function setUp()
    {
        $this->clientMock = $this->getMockBuilder(\G4\DataMapper\Engine\Elasticsearch\ElasticsearchClient::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->collectionNameMock = $this->getMockBuilder(\G4\DataMapper\Engine\Elasticsearch\ElasticsearchCollectionName::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->collectionNameMock
            ->expects($this->any())
            ->method('__toString')
            ->willReturn('nd_api');

        $this->adapter = new ElasticsearchAdapter($this->getMockForElasticsearchClientFactory());
    }

    protected function tearDown()
    {
        $this->adapter = null;
        $this->clientMock = null;
        $this->collectionNameMock = null;
    }

    public function testDelete()
    {
        $selectionFactoryStub = $this->getMockBuilder(\G4\DataMapper\Engine\Elasticsearch\ElasticsearchSelectionFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $selectionFactoryStub
            ->expects($this->once())
            ->method('where')
            ->willReturn(['bool' => ['must' => ['match' => ['id' => '15500']]]]);

        $this->clientMock
            ->expects($this->once())
            ->method('setIndex')
            ->with($this->equalTo((string) $this->collectionNameMock))
            ->willReturnSelf();

        $this->clientMock
            ->expects($this->once())
            ->method('setMethod')
            ->with($this->equalTo(self::METHOD_DELETE))
            ->willReturnSelf();

        $this->clientMock
            ->expects($this->once())
            ->method('setId')
            ->willReturnSelf();

        $this->adapter->delete($this->collectionNameMock, $selectionFactoryStub);
    }

    public function testInsert()
    {
        $body = ['id' => 1, 'first_name' => 'Uncle', 'last_name' => 'Bob', 'gender' => 'm'];

        $mappingMock = $this->getMappingMock();

        $mappingMock
            ->expects($this->once())
            ->method('map')
            ->willReturn($body);

        $this->clientMock
            ->expects($this->once())
            ->method('setIndex')
            ->with($this->equalTo((string) $this->collectionNameMock))
            ->willReturnSelf();

        $this->clientMock
            ->expects($this->once())
            ->method('setMethod')
            ->with($this->equalTo(self::METHOD_POST))
            ->willReturnSelf();

        $this->clientMock
            ->expects($this->once())
            ->method('setId')
            ->with($this->equalTo($body['id']))
            ->willReturnSelf();

        $this->clientMock
            ->expects($this->once())
            ->method('setBody')
            ->with($this->equalTo($body))
            ->willReturnSelf();

        $this->adapter->insert($this->collectionNameMock, $mappingMock);
    }

    public function testInsertWithEmptyData()
    {
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

    public function testInsertBulk()
    {
        $this->expectException(NotImplementedException::class);

        $this->adapter->insertBulk($this->collectionNameMock, new \ArrayIterator());
    }

    public function testUpsertBulk()
    {
        $this->expectException(NotImplementedException::class);

        $this->adapter->upsertBulk($this->collectionNameMock, new \ArrayIterator());
    }

    private function getMappingMock()
    {
        return $this->getMockBuilder(\G4\DataMapper\Common\MappingInterface::class)->getMock();
    }

    public function testUpdate()
    {
        $body = ['id' => 1, 'first_name' => 'Uncle', 'last_name' => 'Bob', 'gender' => 'm'];

        $selectionFactoryStub = $this->getMockBuilder(\G4\DataMapper\Engine\Elasticsearch\ElasticsearchSelectionFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $selectionFactoryStub
            ->expects($this->once())
            ->method('where')
            ->willReturn(['bool' => ['must' => ['match' => ['id' => '1']]]]);

        $mappingMock = $this->getMappingMock();

        $mappingMock
            ->expects($this->once())
            ->method('map')
            ->willReturn($body);

        $this->clientMock
            ->expects($this->once())
            ->method('setIndex')
            ->with($this->equalTo((string) $this->collectionNameMock))
            ->willReturnSelf();

        $this->clientMock
            ->expects($this->once())
            ->method('setMethod')
            ->with($this->equalTo(self::METHOD_PUT))
            ->willReturnSelf();

        $this->clientMock
            ->expects($this->once())
            ->method('setId')
            ->willReturnSelf();

        $this->clientMock
            ->expects($this->once())
            ->method('setBody')
            ->with($this->equalTo(['docs' => $body]))
            ->willReturnSelf();

        $this->adapter->update($this->collectionNameMock, $mappingMock, $selectionFactoryStub);
    }

    public function testUpdateWithEmptyData()
    {
        $selectionFactoryStub = $this->getMockBuilder(\G4\DataMapper\Engine\Elasticsearch\ElasticsearchSelectionFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mappingMock = $this->getMappingMock();

        $mappingMock
            ->expects($this->once())
            ->method('map')
            ->willReturn([]);

        $this->expectException(EmptyDataException::class);
        $this->expectExceptionMessage('Empty data for update.');
        $this->expectExceptionCode(ErrorCode::EMPTY_DATA);

        $this->adapter->update($this->collectionNameMock, $mappingMock, $selectionFactoryStub);
    }

    public function testUpsert()
    {
        $this->expectException(NotImplementedException::class);

        $this->adapter->upsert($this->collectionNameMock, $this->getMappingMock());
    }

    public function testSelect()
    {
        $elasticsearchData = [
            'total' => 2,
            'hits' => [
                'hits' => [
                    ['_source' => ['id' => '1', 'first_name' => 'test', 'last_name' => 'user', 'gender' => 'f']],
                    ['_source' => ['id' => '2', 'first_name' => 'test2', 'last_name' => 'user2', 'gender' => 'm']],
                ]
            ]
        ];

        $data = [
            ['id' => '1', 'first_name' => 'test', 'last_name' => 'user', 'gender' => 'f'],
            ['id' => '2', 'first_name' => 'test2', 'last_name' => 'user2', 'gender' => 'm'],
        ];


        $rawData = new \G4\DataMapper\Common\RawData($data, count($data));

        $selectionFactoryStub = $this->getMockBuilder(\G4\DataMapper\Engine\Elasticsearch\ElasticsearchSelectionFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $selectionFactoryStub
            ->expects($this->once())
            ->method('where')
            ->willReturn(['bool' => ['must' => ['match' => ['id' => '15500']]]]);

        $selectionFactoryStub
            ->expects($this->once())
            ->method('fieldNames')
            ->willReturn(['first_name', 'last_name']);

        $selectionFactoryStub
            ->expects($this->once())
            ->method('limit')
            ->willReturn('1');

        $selectionFactoryStub
            ->expects($this->once())
            ->method('sort')
            ->willReturn(['id' => ['order' => 'asc']]);

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
            ->method('setIndex')
            ->with($this->equalTo((string) $this->collectionNameMock))
            ->willReturnSelf();

        $this->clientMock
            ->expects($this->once())
            ->method('setBody')
            ->willReturnSelf();

        $this->clientMock
            ->expects($this->once())
            ->method('search')
            ->willReturnSelf();

        $this->clientMock->expects($this->once())->method('getResponse')->willReturn($elasticsearchData['hits']);

        $select = $this->adapter->select($this->collectionNameMock, $selectionFactoryStub);

        $this->assertInstanceOf(\G4\DataMapper\Common\RawData::class, $select);
        $this->assertEquals($rawData->count(), $select->count());
        $this->assertEquals($rawData->getAll(), $select->getAll());
    }

    public function testQuery()
    {
        $this->expectException(NotImplementedException::class);

        $this->adapter->query('Some test query');
    }

    private function getMockForElasticsearchClientFactory()
    {
        $clientFactoryStub = $this->getMockBuilder(\G4\DataMapper\Engine\Elasticsearch\ElasticsearchClientFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $clientFactoryStub->method('create')
            ->willReturn($this->clientMock);

        return $clientFactoryStub;
    }
}
