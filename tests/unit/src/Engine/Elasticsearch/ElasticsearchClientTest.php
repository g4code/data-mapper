<?php


use G4\DataMapper\Engine\Elasticsearch\ElasticsearchClient;
use G4\DataMapper\Exception\ClientException;
use G4\ValueObject\Url;

class ElasticsearchClientTest extends \PHPUnit\Framework\TestCase
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

    public function testGetUrl()
    {
        $this->assertEquals($this->urlMock, $this->elasticsearchClient->getUrl());
    }

    public function testCurlError()
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionMessage('Unexpected response code:Curl error number - 6 from ES has been returned on submit. More info: "Could not resolve host: nothing". Url: http://nothing//doc/. Body: null. Response: ');
        $elasticsearchClient = new ElasticsearchClient(new Url('http://nothing/'), null, 5);
        $elasticsearchClient->execute(); // todo - misleading because execute is only called internally
    }

    protected function setUp(): void
    {
        $this->urlMock = $this->getMockBuilder(Url::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->elasticsearchClient = new ElasticsearchClient($this->urlMock, null, 5);
    }

    protected function tearDown(): void
    {
        $this->urlMock              = null;
        $this->elasticsearchClient  = null;
    }
}
