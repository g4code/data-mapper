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

    /**
     * @param SelectionIdentity $identity
     * @return CollectionContent
     */
    public function find(SelectionIdentity $identity = null)
    {
        $this->response = $this->adapter->search($this->getSelectionFactory()->query($identity));
        print_r($this->response);
        return $this->returnCollection();
    }

    public function flush()
    {
        return $this->adapter->flush();
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
        return $this->adapter->index($domain->getRawData(), $domain->getId());
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
        return empty($this->response['hits']['hits'])
            ? []
            : $this->response['hits']['hits'];
    }

    /**
     * @return SelectionFactory
     */
    private function getSelectionFactory()
    {
        if ($this->selectionFactory === null) {
            $this->selectionFactory = new SelectionFactory();
        }
        return $this->selectionFactory;
    }

    /**
     * @return int
     */
    private function getTotalItemsCount()
    {
        return empty($this->response['hits']['total'])
            ? 0
            : $this->response['hits']['total'];
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