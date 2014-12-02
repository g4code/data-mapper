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
     * @throws \Exception
     * @return string
     */
    public function getFactoryDomainName()
    {
        if (empty($this->factoryDomainName)) {
            throw new \Exception('factoryDomainName is not set!');
        }
        return $this->factoryDomainName;
    }

    /**
     * @return G4\DataMapper\Selection\Solr\Identity
     */
    public function getIdentity()
    {
        return new \G4\DataMapper\Selection\Solr\Identity();
    }

    /**
     * @return array
     */
    public function getRawData()
    {
        return empty($this->response["response"]["docs"])
            ? []
            : $this->response["response"]["docs"];
    }

    /**
     * @return G4\DataMapper\Selection\Solr\Factory
     */
    public function getSelectionFactory()
    {
        if ($this->selectionFactory === null) {
            $this->selectionFactory = new \G4\DataMapper\Selection\Solr\Factory();
        }
        return $this->selectionFactory;
    }

    /**
     * @return int
     */
    public function getTotalItemsCount()
    {
        return empty($this->response['response']['numFound'])
            ? 0
            : $this->response['response']['numFound'];;
    }

    /**
     * @return \G4\DataMapper\Collection\Content
     */
    public function returnCollection()
    {
        return new \G4\DataMapper\Collection\Content($this->getRawData(), $this->getFactoryDomainName(), $this->getTotalItemsCount());
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

    /**
     * @return \G4\DataMapper\Mapper\Solr
     */
    private function fetch(\G4\DataMapper\Selection\Identity $identity)
    {
        $output = $this->adapter->select($this->getSelectionFactory()->requestParams($identity), $this->getSelectionFactory()->query($identity));

        $this->response = json_decode($output, true);

        return $this;
    }
}