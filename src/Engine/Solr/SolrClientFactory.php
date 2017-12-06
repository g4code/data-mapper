<?php

namespace G4\DataMapper\Engine\Solr;

use G4\ValueObject\Url;
use G4\ValueObject\PortNumber;
use G4\Factory\CreateInterface;

class SolrClientFactory implements CreateInterface
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

        $client = new SolrClient($url->port(new PortNumber($this->params['port'])));

        return $client;
    }

    private function filterParams(array $params)
    {
        if (empty($params['host'])) {
            throw new \Exception('No host param', 101);
        }

        if (empty($params['port'])) {
            throw new \Exception('No port param', 101);
        }

        $this->params = [
            'host'     => $params['host'],
            'port'     => $params['port'],
        ];
    }
}
