<?php

namespace G4\DataMapper\Mapper;

use G4\DataMapper\Selection\Elasticsearch\Factory as SelectionFactory;
use G4\DataMapper\Selection\Elasticsearch\Identity as SelectionIdentity;
use G4\DataMapper\Adapter\Elasticsearch\Client as Adapter;
use G4\DataMapper\Collection\Content as CollectionContent;
use G4\DataMapper\Domain\DomainAbstract;

class Elasticsearch
{

    /**
     * @var Adapter
     */
    private $adapter;

    /**
     * @var string
     */
    private $factoryDomainName;

    /**
     * @var array
     */
    private $response;

    /**
     * @var SelectionFactory
     */
    private $selectionFactory;

    /**
     * @param Adapter $adapter
     */
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function create($params)
    {
        $this->getSelectionFactory()->setMappings($params);
        return $this->adapter->putMapping($this->getSelectionFactory());
    }

    public function bulkUpdate(\G4\DataMapper\Bulk\Elasticsearch $bulk)
    {
        return $this->adapter->bulk($bulk);
    }

    /**
     * @param SelectionIdentity $identity
     * @return CollectionContent
     */
    public function find(SelectionIdentity $identity = null)
    {
        $this->getSelectionFactory()->setIdentity($identity);
        $this->response = $this->adapter->search($this->getSelectionFactory());
        return $this->returnCollection();
    }

    public function flush()
    {
        return $this->adapter->deleteMapping($this->getSelectionFactory());
    }

    public function getBulk()
    {
        return new \G4\DataMapper\Bulk\Elasticsearch($this->getSelectionFactory());
    }

    /**
     * @return SelectionIdentity
     */
    public function getIdentity()
    {
        return new SelectionIdentity();
    }

    public function setFactoryDomainName($factoryDomainName)
    {
        $this->factoryDomainName = $factoryDomainName;
        return $this;
    }

    public function update(DomainAbstract $domain)
    {
        $this->getSelectionFactory()
            ->setBody($domain->getRawData())
            ->setId($domain->getId());
        return $this->adapter->index($this->getSelectionFactory());
    }

    public function updateAdd(DomainAbstract $domain)
    {
        $this->getSelectionFactory()
            ->setBody($domain->getRawData())
            ->setId($domain->getId());
        return $this->adapter->updateAppend($this->getSelectionFactory());
    }

    public function updateSet(DomainAbstract $domain)
    {
        $this->getSelectionFactory()
            ->setBody($domain->getRawData())
            ->setId($domain->getId());
        return $this->adapter->update($this->getSelectionFactory());
    }

    /**
     * @throws \Exception
     * @return string
     */
    private function getFactoryDomainName()
    {
        if (empty($this->factoryDomainName)) {
            throw new \Exception('factoryDomainName is not set!');
        }
        return $this->factoryDomainName;
    }

    /**
     * @return array
     */
    private function getRawData()
    {
        $hits = [];
        if (!empty($this->response['hits']['hits'])) {
            $hits = $this->response['hits']['hits'];
        }
        if (!empty($this->response['aggregations'])) {
            $hits = array_map(
                function($value){
                    return $value['group_by_hits']['hits']['hits'][0];
                },
                $this->response['aggregations']['group_by']['buckets']
            );
        }
        return $hits;
    }

    /**
     * @return SelectionFactory
     */
    private function getSelectionFactory()
    {
        if ($this->selectionFactory === null) {
            $this->selectionFactory = new SelectionFactory();
            $this->selectionFactory
                ->setIndexName($this->adapter->getIndex())
                ->setTypeName($this->adapter->getType());
        }
        return $this->selectionFactory;
    }

    /**
     * @return int
     */
    private function getTotalItemsCount()
    {
        $totalItemsCount = 0;
        if (!empty($this->response['hits']['total'])) {
            $totalItemsCount = $this->response['hits']['total'];
        }
        if (!empty($this->response['aggregations']) && !empty($this->response['aggregations']['group_by']['buckets'])) {
            $totalItemsCount = count($this->response['aggregations']['group_by']['buckets']);
        }
        return $totalItemsCount;
    }

    /**
     * @return CollectionContent
     */
    private function returnCollection()
    {
        $transformedData = [];
        $rawData = $this->getRawData();
        if (is_array($rawData) && count($rawData) > 0) {
            foreach ($rawData as $key => $value) {
                $transformedData[empty($value['_id']) ? $key : $value['_id']] = $value['_source'];
            }
        }
        return new CollectionContent($transformedData, $this->getFactoryDomainName(), $this->getTotalItemsCount());
    }
}