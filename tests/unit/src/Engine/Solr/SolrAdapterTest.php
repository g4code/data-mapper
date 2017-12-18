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

    public function testSelect()
    {
        $data = ['documents' =>
                    ['id' => '1', 'first_name' => 'test', 'last_name' => 'user', 'gender' => 'f'],
                    ['id' => '2', 'first_name' => 'test2', 'last_name' => 'user2', 'gender' => 'm'],
                ];

        $selectionFactoryStub = $this->getMockBuilder('\G4\DataMapper\Engine\Solr\SolrSelectionFactory')
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
            ->willReturn($data);

        $select = $this->adapter->select($this->collectionNameMock, $selectionFactoryStub);

        $rawData = new \G4\DataMapper\Common\RawData($data, 2);

        $this->assertInstanceOf('\G4\DataMapper\Common\RawData', $select);
        $this->assertEquals($rawData->count(), $select->count());
        $this->assertEquals($rawData->getAll(), $select->getAll());
    }

    public function testUpdate()
    {
        $mappingMock = $this->getMockBuilder('\G4\DataMapper\Common\MappingInterface')
            ->getMock();

        $mappingMock
            ->expects($this->once())
            ->method('map')
            ->willReturn(['id' => 1, 'first_name' => 'Bob', 'last_name' => 'Uncle', 'gender' => 'm']);

        $this->clientMock
            ->expects($this->once())
            ->method('setCollection')
            ->with($this->equalTo((string) $this->collectionNameMock))
            ->willReturnSelf();

        $this->clientMock
            ->expects($this->once())
            ->method('setDocument')
            ->with($this->equalTo(['id' => 1, 'first_name' => 'Bob', 'last_name' => 'Uncle', 'gender' => 'm']))
            ->willReturnSelf();

        $this->clientMock->expects($this->once())
            ->method('update');

        $this->adapter->update($this->collectionNameMock, $mappingMock);
    }
}
