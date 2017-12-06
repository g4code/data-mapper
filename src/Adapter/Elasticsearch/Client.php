<?php

namespace G4\DataMapper\Adapter\Elasticsearch;

use Elasticsearch\ClientBuilder;
use Elasticsearch\Client as ElasticsearchClient;
use Elasticsearch\Namespaces\IndicesNamespace;
use Elasticsearch\Transport;
use G4\DataMapper\Selection\Elasticsearch\Factory as SelectionFactory;
use G4\DataMapper\Bulk\Elasticsearch as Bulk;

class Client
{

    /**
     * @var ElasticsearchClient
     */
    private $client;

    /**
     * @var IndicesNamespace
     */
    private $clientIndices;

    /**
     * @var string
     */
    private $index;

    /**
     * @var array
     */
    private $hosts;

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
        $this->client        = ClientBuilder::create()->setHosts($this->hosts)->build();
        $this->clientIndices = $this->client->indices();
    }

    public function bulk(Bulk $bulk)
    {
        $result = [];
        try {
            $result = $this->client->bulk($bulk->getData());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
        return $result;
    }

    public function putMapping(SelectionFactory $selectionFactory)
    {
        if (!$this->clientIndices->exists($selectionFactory->prepareIndex())) {
            $this->clientIndices->create($selectionFactory->prepareIndex());
        }
        return $this->clientIndices->putMapping($selectionFactory->prepareForMapping());
    }

    public function deleteMapping(SelectionFactory $selectionFactory)
    {
        return $this->clientIndices->exists($selectionFactory->prepareIndex()) && $this->clientIndices->existsType($selectionFactory->prepareType())
            ? $this->clientIndices->deleteMapping($selectionFactory->prepareType())
            : true;
    }

    public function index(SelectionFactory $selectionFactory)
    {
        $result = [];
        try {
            $result = $this->client->index($selectionFactory->prepareForIndexing());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
        return $result;
    }

    public function update(SelectionFactory $selectionFactory)
    {
        $result = [];
        try {
            $result = $this->client->update($selectionFactory->prepareForUpdate());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
        return $result;
    }

    public function updateAppend(SelectionFactory $selectionFactory)
    {
        $result = [];
        try {
            $result = $this->client->update($selectionFactory->prepareForUpdateAppend());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
        return $result;
    }

//     public function get($params)
//     {
//         return $this->client->get($params);
//     }

    //TODO: Drasko: This needs refactoring!!!
    public function search(SelectionFactory $selectionFactory)
    {
        $result = [];
        try {
            $result = $this->client->search($selectionFactory->query());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
        return $result;
    }

    public function getIndex()
    {
        return $this->index;
    }

    public function getType()
    {
        return $this->type;
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
     * @param array $params
     */
    private function filterParams(array $params)
    {
        $this->index = $params['index'];
        $this->type  = $params['type'];
        $this->hosts = $params['hosts'];
    }
}
