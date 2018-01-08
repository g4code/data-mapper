<?php

namespace G4\DataMapper\Engine\Elasticsearch;

use G4\ValueObject\Url;
use G4\ValueObject\PortNumber;
use G4\Factory\CreateInterface;
use G4\DataMapper\Exception\NoHostParameterException;
use G4\DataMapper\Exception\NoPortParameterException;


class ElasticsearchClientFactory implements CreateInterface
{
    const COLON         = ':';
    const FORWARD_SLASH = '/';
    const PROTOCOL      = 'http';

    /**
     * @var array
     */
    private $params;

    public function __construct(array $params)
    {
        $this->filterParams($params);
    }

    public function create()
    {
        $url = new Url(self::PROTOCOL . self::COLON . self::FORWARD_SLASH . self::FORWARD_SLASH . $this->params['host']);

        $client = new ElasticsearchClient($url->port(new PortNumber($this->params['port'])));

        return $client;
    }

    private function filterParams(array $params)
    {
        if (empty($params['host'])) {
            throw new NoHostParameterException();
        }

        if (empty($params['port'])) {
            throw new NoPortParameterException();
        }

        $this->params = [
            'host'     => $params['host'],
            'port'     => $params['port'],
        ];
    }
}
