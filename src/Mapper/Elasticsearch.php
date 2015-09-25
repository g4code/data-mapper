<?php

namespace G4\DataMapper\Mapper;

class Elasticsearch
{

    /**
     * @var unknown
     */
    private $adapter;

    private $factoryDomainName;

    private $response;

    private $selectionFactory;

    /**
     * @param \G4\DataMapper\Adapter\Elasticsearch\Client $adapter
     */
    public function __construct(\G4\DataMapper\Adapter\Elasticsearch\Client $adapter)
    {
        $this->adapter = $adapter;
    }

    public function find($identity = null)
    {
        $this->response = $this->adapter->search($this->getSelectionFactory()->query($identity));
        return $this->returnCollection();
    }

    public function flush()
    {
        return $this->adapter->flush();
    }

    public function getIdentity()
    {
        return new \G4\DataMapper\Selection\Elasticsearch\Identity();
    }

    public function setFactoryDomainName($factoryDomainName)
    {
        $this->factoryDomainName = $factoryDomainName;
        return $this;
    }

    public function update(\G4\DataMapper\Domain\DomainAbstract $domain)
    {
        return $this->adapter->index($domain->getRawData(), $domain->getId());
    }

    private function getFactoryDomainName()
    {
        if (empty($this->factoryDomainName)) {
            throw new \Exception('factoryDomainName is not set!');
        }
        return $this->factoryDomainName;
    }

    private function getRawData()
    {
        return empty($this->response['hits']['hits'])
            ? []
            : $this->response['hits']['hits'];
    }

    private function getSelectionFactory()
    {
        if ($this->selectionFactory === null) {
            $this->selectionFactory = new \G4\DataMapper\Selection\Elasticsearch\Factory();
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

    private function returnCollection()
    {
        $transformedData = [];
        $rawData = $this->getRawData();
        if (is_array($rawData) && count($rawData) > 0) {
            foreach ($rawData as $key => $value) {
                $transformedData[empty($value['_id']) ? $key : $value['_id']] = $value['_source'];
            }
        }
        return new \G4\DataMapper\Collection\Content($transformedData, $this->getFactoryDomainName(), $this->getTotalItemsCount());
    }
}