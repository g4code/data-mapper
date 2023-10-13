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
        $host = parse_url($this->params['host'], PHP_URL_HOST) ?: $this->params['host'];
        $protocol = parse_url($this->params['host'], PHP_URL_SCHEME) ?: self::PROTOCOL;
        $port = parse_url($this->params['host'], PHP_URL_PORT) ?: $this->params['port'];
        $username = parse_url($this->params['host'], PHP_URL_USER);
        $password = parse_url($this->params['host'], PHP_URL_PASS);

        $url = new Url(
            $protocol .
            self::COLON .
            self::FORWARD_SLASH .
            self::FORWARD_SLASH .
            $host
        );

        if ($username && $password) {
            $url = $url->credentials($username, $password);
        }

        return new ElasticsearchClient(
            $url->port(new PortNumber($port)),
            $this->params['index_type'],
            $this->params['timeout'],
            $this->params['version']
        );
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
            'timeout' => isset($params['timeout']) ? $params['timeout'] : null,
            'version'  => isset($params['version']) ? $params['version'] : null,
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
