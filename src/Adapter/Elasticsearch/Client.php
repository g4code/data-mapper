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
     * @var string
     */
    private $index;

    /**
     * @var array
     */
    private $params;

//     /**
//      * @var \G4\DataMapper\Profiler\Solr\Ticker
//      */
//     private $profiler;

    /**
     * @var string
     */
    private $type;


    /**
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->filterParams($params);

        $this->client = new ElasticsearchClient($this->params);
    }


    public function index(array $body, $id)
    {
        return $this->client->index($this->prepareForIndexing($body, $id));
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

//     public function create($params)
//     {
//         return $this->client->create($params);
//     }

    /**
     * @param array $body
     * @return array
     */
    private function prepareForIndexing(array $body, $id)
    {
        $prepared = [
            'index' => $this->index,
            'type'  => $this->type,
            'body'  => $body,
        ];
        if ($id !== null) {
            $prepared['id'] = $id;
        }
        return $prepared;
    }

    private function filterParams($params)
    {
        $this->index  = $params['index'];
        $this->type   = $params['type'];
        $this->params = [
            'hosts' => [
                $params['host'] . ':' . $params['port'],
            ]
        ];
    }
}