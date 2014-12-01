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
     * @var G4\DataMapper\Selection\Solr\Factory
     */
    private $selectionFactory;

    /**
     * @var array
     */
    private $rawData;

    /**
     * @var int
     */
    private $totalItemsCount;


    public function __construct($selectUrl)
    {
        $this->adapter = new \G4\DataMapper\Adapter\Solr\Curl($selectUrl);

        $this->rawData         = [];
        $this->totalItemsCount = 0;
    }

    /**
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
     * @return \G4\DataMapper\Collection\Content
     */
    public function returnCollection()
    {
        return new \G4\DataMapper\Collection\Content($this->rawData, $this->getFactoryDomainName(), $this->totalItemsCount);
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

    private function fetch(\G4\DataMapper\Selection\Identity $identity)
    {
        $output = $this->adapter
            ->setRequestParams($this->getSelectionFactory()->requestParams($identity))
            ->setQuery($this->getSelectionFactory()->query($identity))
            ->connect()
            ->getResponse();

        $resultFeed = json_decode($output, true);

        $this->totalItemsCount = empty($resultFeed['response']['numFound'])
            ? 0
            : $resultFeed['response']['numFound'];
        $this->rawData         = empty($resultFeed["response"]["docs"])
            ? array()
            : $resultFeed["response"]["docs"];

        return $this;
    }
}