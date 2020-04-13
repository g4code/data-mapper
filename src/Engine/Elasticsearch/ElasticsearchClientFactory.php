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

    /**
     * ElasticsearchClientFactory constructor.
     * @param array $params
     * @throws NoHostParameterException
     * @throws NoPortParameterException
     */
    public function __construct(array $params)
    {
        $this->filterParams($params);
    }

    /**
     * @return ElasticsearchClient
     * @throws \G4\ValueObject\Exception\InvalidIntegerNumberException
     */
    public function create()
    {
        $url = new Url(
            self::PROTOCOL .
            self::COLON .
            self::FORWARD_SLASH .
            self::FORWARD_SLASH .
            $this->params['host']
        );

        return new ElasticsearchClient($url->port(new PortNumber($this->params['port'])), $this->params['index_type']);
    }

    /**
     * @param array $params
     * @throws NoHostParameterException
     * @throws NoPortParameterException
     */
    private function filterParams(array $params)
    {
        if (empty($params['host'])) {
            throw new NoHostParameterException();
        }

        if (empty($params['port'])) {
            throw new NoPortParameterException();
        }

        $this->params = [
            'host'     => $this->handleHostParameter($params['host']),
            'port'     => $params['port'],
            'index_type' => isset($params['index_type']) ? $params['index_type'] : null,
        ];
    }

    /**
     * @param $host
     * @return mixed
     */
    private function handleHostParameter($host)
    {
        if (is_array($host)) {
            $hostsArray = array_filter($host);
            $randomKey = array_rand($hostsArray);

            return $hostsArray[$randomKey];
        }

        return $host;
    }
}
