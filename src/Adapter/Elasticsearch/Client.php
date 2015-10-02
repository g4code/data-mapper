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

    public function flush()
    {
        return $this->client->indices()->deleteMapping($this->prepareBasics());
    }

    public function index(array $body, $id)
    {
        return $this->client->index($this->prepareForIndexing($body, $id));
    }

//     public function get($params)
//     {
//         return $this->client->get($params);
//     }

    public function search($body)
    {
        echo '<pre>';
        print_r($this->prepareForIndexing($body));
        return $this->client->search($this->prepareForIndexing($body));
    }

//     public function delete()
//     {
//         return $this->client->delete();
//     }

//     public function create($params)
//     {
//         return $this->client->create($params);
//     }

    /**
     * @return array
     */
    private function prepareBasics()
    {
        return [
            'index' => $this->index,
            'type'  => $this->type,
        ];
    }

    /**
     * @param array $body
     * @param string $id
     * @return array
     */
    private function prepareForIndexing(array $body, $id = null)
    {
        $prepared = $this->prepareBasics() + [
            'body'  => $body,
        ];
        if ($id !== null) {
            $prepared['id'] = $id;
        }
        return $prepared;
    }

    /**
     * @param array $params
     */
    private function filterParams(array $params)
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