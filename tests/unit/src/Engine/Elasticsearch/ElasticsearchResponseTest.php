<?php


use G4\DataMapper\Engine\Elasticsearch\ElasticsearchResponse;


class ElasticsearchResponseTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var string
     */
    private $dataWithHits;

    /**
     * @var string
     */
    private $dataWithError;

    /**
     * @var string
     */
    private $errorMessage;


    public function testGetHits()
    {
        $elasticsearchResponse = new ElasticsearchResponse($this->dataWithHits);

        $this->assertFalse($elasticsearchResponse->hasError());
        $this->assertArrayHasKey('total', $elasticsearchResponse->getHits());
        $this->assertArrayHasKey('max_score', $elasticsearchResponse->getHits());
        $this->assertArrayHasKey('hits', $elasticsearchResponse->getHits());
    }

    public function testGetHitsWithError()
    {
        $elasticsearchResponse = new ElasticsearchResponse($this->dataWithError);

        $this->assertTrue($elasticsearchResponse->hasError());
        $this->assertEquals($this->errorMessage, $elasticsearchResponse->getErrorMessage());
        $this->assertArrayNotHasKey('total', $elasticsearchResponse->getHits());
        $this->assertArrayNotHasKey('max_score', $elasticsearchResponse->getHits());
        $this->assertArrayNotHasKey('hits', $elasticsearchResponse->getHits());
    }

    public function testGetTotal()
    {
        $elasticsearchResponse = new ElasticsearchResponse($this->dataWithHits);

        $this->assertFalse($elasticsearchResponse->hasError());
        $this->assertEquals(1, $elasticsearchResponse->getTotal());
    }

    public function testGetTotalWithError()
    {
        $elasticsearchResponse = new ElasticsearchResponse($this->dataWithError);

        $this->assertEquals(0, $elasticsearchResponse->getTotal());
    }

    protected function setUp()
    {
        $this->dataWithHits = '{"took":16,"timed_out":false,"_shards":{"total":1,"successful":1,"failed":0},"hits":{"total":1,"max_score":null,"hits":[1]}}';
        $this->dataWithError = '{"error":{"root_cause":[{"type":"query_parsing_exception","reason":"field [location] is not a geo_point field","index":"profiles","line":1,"col":270}],"type":"search_phase_execution_exception","reason":"all shards failed","phase":"query_fetch","grouped":true,"failed_shards":[{"shard":0,"index":"profiles","node":"75N9T595S-eqHYV8_o08ng","reason":{"type":"query_parsing_exception","reason":"field [location] is not a geo_point field","index":"profiles","line":1,"col":270}}]},"status":400}';

        $this->errorMessage = '["search_phase_execution_exception",[{"type":"query_parsing_exception","reason":"field [location] is not a geo_point field","index":"profiles","line":1,"col":270}]]';
    }

    protected function tearDown()
    {
        $this->dataWithHits = null;
        $this->dataWithError = null;
    }
}
