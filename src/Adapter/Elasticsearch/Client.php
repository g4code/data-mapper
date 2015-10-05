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

    public function putMapping($params)
    {
        if (!$this->client->indices()->exists($this->prepareIndex())) {
            $this->client->indices()->create($this->prepareIndex());
        }
        return $this->client->indices()->putMapping($this->prepareForIndexing($params));
    }

    public function deleteMapping()
    {
        //add check if index and type exists
        return $this->client->indices()->deleteMapping($this->prepareType());
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

    private function prepareIndex()
    {
        return [
            'index' => $this->index,
        ];
    }

    /**
     * @return array
     */
    private function prepareType()
    {
        return $this->prepareIndex() + [
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
        $prepared = $this->prepareType() + [
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