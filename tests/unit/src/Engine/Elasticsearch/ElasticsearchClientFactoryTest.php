<?php

use G4\DataMapper\Engine\Elasticsearch\ElasticsearchClientFactory;
use G4\DataMapper\Exception\NoHostParameterException;
use G4\DataMapper\Exception\NoPortParameterException;
use G4\DataMapper\ErrorCodes as ErrorCode;
use G4\DataMapper\ErrorMessages as ErrorMessage;

class ElasticsearchClientFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var array
     */
    private $params;

    /**
     * @var ElasticsearchClientFactory
     */
    private $clientFactory;


    protected function setUp()
    {
        $this->params = [
            'host'     => '127.0.0.1',
            'port'     => '9200',
        ];

        $this->clientFactory = new ElasticsearchClientFactory($this->params);

    }

    protected function tearDown()
    {
        $this->params = null;
        $this->clientFactory = null;
    }

    public function testCreate()
    {
        $this->assertInstanceOf(\G4\DataMapper\Engine\Elasticsearch\ElasticsearchClient::class, $this->clientFactory->create());
    }

    public function testParamsWithNoHost()
    {
        unset($this->params['host']);
        $this->expectException(NoHostParameterException::class);
        $this->expectExceptionMessage(ErrorMessage::NO_HOST_PARAMETER);
        $this->expectExceptionCode(ErrorCode::NO_HOST_PARAMETER);
        new ElasticsearchClientFactory($this->params);
    }

    public function testParamsWithNoPort()
    {
        unset($this->params['port']);
        $this->expectException(NoPortParameterException::class);
        $this->expectExceptionMessage(ErrorMessage::NO_PORT_PARAMETER);
        $this->expectExceptionCode(ErrorCode::NO_PORT_PARAMETER);
        new ElasticsearchClientFactory($this->params);
    }
}
