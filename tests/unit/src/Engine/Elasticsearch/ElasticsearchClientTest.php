<?php


use G4\DataMapper\Engine\Elasticsearch\ElasticsearchClient;
use G4\ValueObject\Url;

class ElasticsearchClientTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var ElasticsearchClient
     */
    private $elasticsearchClient;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    private $urlMock;

    public function testGetResponseWithData()
    {
        $responseString = '{"took":16,"timed_out":false,"_shards":{"total":1,"successful":1,"failed":0},"hits":{"total":3,"max_score":null,"hits":[1,2,3]}}';
        $this->elasticsearchClient->responseFactory($responseString);
        $this->assertTrue(is_array($this->elasticsearchClient->getResponse()));
        $this->assertTrue(array_key_exists('total', $this->elasticsearchClient->getResponse()));
        $this->assertTrue(array_key_exists('max_score', $this->elasticsearchClient->getResponse()));
        $this->assertTrue(array_key_exists('hits', $this->elasticsearchClient->getResponse()));
        $this->assertTrue(is_array($this->elasticsearchClient->getResponse()['hits']));
        $this->assertEquals(3, $this->elasticsearchClient->getTotalItemsCount());
    }

    public function testGetResponseWithError()
    {
        $responseString = '{"error":"message","status":400}';
        $this->elasticsearchClient->responseFactory($responseString);
        $this->assertEquals([], $this->elasticsearchClient->getResponse());
        $this->assertEquals(0, $this->elasticsearchClient->getTotalItemsCount());
    }

    protected function setUp()
    {
        $this->urlMock = $this->getMockBuilder(Url::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->elasticsearchClient = new ElasticsearchClient($this->urlMock);
    }

    protected function tearDown()
    {
        $this->urlMock              = null;
        $this->elasticsearchClient  = null;
    }
}
