<?php

use G4\DataMapper\Engine\Solr\SolrClientFactory;
use G4\DataMapper\Exception\NoHostParameterException;
use G4\DataMapper\Exception\NoPortParameterException;
use G4\DataMapper\ErrorCodes as ErrorCode;
use G4\DataMapper\ErrorMessages as ErrorMessage;

class SolrClientFactoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var array
     */
    private $params;

    /**
     * @var SolrClientFactory
     */
    private $clientFactory;


    protected function setUp(): void
    {
        $this->params = [
            'host'     => '127.0.0.1',
            'port'     => '8983',
        ];

        $this->clientFactory = new SolrClientFactory($this->params);

    }

    protected function tearDown(): void
    {
        $this->params = null;
        $this->clientFactory = null;
    }

    public function testCreate()
    {
        $this->assertInstanceOf(\G4\DataMapper\Engine\Solr\SolrClient::class, $this->clientFactory->create());
    }

    public function testParamsWithNoHost()
    {
        unset($this->params['host']);
        $this->expectException(NoHostParameterException::class);
        $this->expectExceptionMessage(ErrorMessage::NO_HOST_PARAMETER);
        $this->expectExceptionCode(ErrorCode::NO_HOST_PARAMETER);
        new SolrClientFactory($this->params);
    }

    public function testParamsWithNoPort()
    {
        unset($this->params['port']);
        $this->expectException(NoPortParameterException::class);
        $this->expectExceptionMessage(ErrorMessage::NO_PORT_PARAMETER);
        $this->expectExceptionCode(ErrorCode::NO_PORT_PARAMETER);
        new SolrClientFactory($this->params);
    }
}
