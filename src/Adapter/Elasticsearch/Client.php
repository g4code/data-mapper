<?php

namespace G4\DataMapper\Adapter\Elasticsearch;

use Elasticsearch\Client as ElasticsearchClient;

class Client
{

    /**
     * @var ElasticsearchClient
     */
    private $client;

    /**
     * @var array
     */
    private $params;

    /**
     * @var \G4\DataMapper\Profiler\Solr\Ticker
     */
    private $profiler;


    /**
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->filterParams($params);

        $this->client = new ElasticsearchClient($this->params);
    }



    public function index($params)
    {
        return $this->client->index($params);
    }

    public function get($params)
    {
        return $this->client->get($params);
    }

    public function search($params)
    {
        return $this->client->search($params);
    }

    public function delete($params)
    {
        return $this->client->delete($params);
    }

    public function create($params)
    {
        return $this->client->create($params);
    }



    private function filterParams($params)
    {
        $this->params = [
            'hosts' => [
                $params['host'] . ':' . $params['port'],
            ]
        ];
    }
}