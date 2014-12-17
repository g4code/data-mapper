<?php

namespace G4\DataMapper\Mapper;

class Solr
{

    /**
     * @var \G4\DataMapper\Adapter\Solr\Curl
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
     * @var G4\DataMapper\Selection\Solr\Factory
     */
    private $selectionFactory;


    /**
     * @param \G4\DataMapper\Adapter\Solr\Curl $adapter
     */
    public function __construct(\G4\DataMapper\Adapter\Solr\Curl $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @param \G4\DataMapper\Selection\Identity $identity
     * @return \G4\DataMapper\Collection\Content
     */
    public function find(\G4\DataMapper\Selection\Identity $identity = null)
    {
        return $this
            ->fetch($identity === null ? $this->getIdentity() : $identity)
            ->returnCollection();
    }

    /**
     * @return G4\DataMapper\Selection\Solr\Identity
     */
    public function getIdentity()
    {
        return new \G4\DataMapper\Selection\Solr\Identity();
    }

    /**
     * @param string $factoryDomainName
     * @return \G4\DataMapper\Mapper\Solr
     */
    public function setFactoryDomainName($factoryDomainName)
    {
        $this->factoryDomainName = $factoryDomainName;
        return $this;
    }

    public function update(array $data)
    {
        $this->response = $this->adapter
            ->setDocument($data)
            ->update();
        return isset($this->response["responseHeader"]["status"])
            && $this->response["responseHeader"]["status"] === 0;
    }

    /**
     * @return \G4\DataMapper\Mapper\Solr
     */
    private function fetch(\G4\DataMapper\Selection\Identity $identity)
    {
        $this->response = $this->adapter
            ->setRequestParams($this->getSelectionFactory()->requestParams($identity))
            ->setQuery($this->getSelectionFactory()->query($identity))
            ->select();
        return $this;
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
        return empty($this->response["response"]["docs"])
            ? []
            : $this->response["response"]["docs"];
    }

    /**
     * @return G4\DataMapper\Selection\Solr\Factory
     */
    private function getSelectionFactory()
    {
        if ($this->selectionFactory === null) {
            $this->selectionFactory = new \G4\DataMapper\Selection\Solr\Factory();
        }
        return $this->selectionFactory;
    }

    /**
     * @return int
     */
    private function getTotalItemsCount()
    {
        return empty($this->response['response']['numFound'])
            ? 0
            : $this->response['response']['numFound'];;
    }

    /**
     * @return \G4\DataMapper\Collection\Content
     */
    private function returnCollection()
    {
        return new \G4\DataMapper\Collection\Content($this->getRawData(), $this->getFactoryDomainName(), $this->getTotalItemsCount());
    }
}