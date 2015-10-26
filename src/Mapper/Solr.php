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


    public function bulkUpdate(\G4\DataMapper\Bulk\Solr $bulk)
    {
        if ($bulk->hasData()) {
            $this->response = $this->adapter
                ->setDocument($bulk->getData())
                ->update();
        }
        if ($bulk->hasDataForDelete()) {
            $this->response = $this->adapter
                ->setDocument($bulk->getDataForDelete())
                ->update();
        }
        return $this->getResponseStatus();
    }

    public function delete(\G4\DataMapper\Domain\DomainAbstract $domain)
    {
        return $this->bulkUpdate($this->getBulk()->markForDelete($domain));
    }

    public function deleteByIdentity(\G4\DataMapper\Selection\IdentityAbstract $identity)
    {
        return $this->bulkUpdate($this->getBulk()->markForDeleteByIdentity($identity));
    }

    /**
     * @param \G4\DataMapper\Selection\Identity $identity
     * @return \G4\DataMapper\Collection\Content
     */
    public function find(\G4\DataMapper\Selection\IdentityAbstract $identity = null)
    {
        return $this
            ->fetch($identity === null ? $this->getIdentity() : $identity)
            ->returnCollection();
    }

    public function flush()
    {
        return $this->adapter
            ->setDocument([
                \G4\DataMapper\Bulk\Solr::METHOD_DELETE => [
                    'query' => '*:*',
                ]
            ])
            ->flush();
    }

    public function getBulk()
    {
        return new \G4\DataMapper\Bulk\Solr();
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

    public function update(\G4\DataMapper\Domain\DomainAbstract $domain)
    {
        return $this->bulkUpdate($this->getBulk()->markForUpdate($domain));
    }

    public function updateAdd(\G4\DataMapper\Domain\DomainAbstract $domain)
    {
        return $this->bulkUpdate($this->getBulk()->markForAdd($domain));
    }

    public function updateSet(\G4\DataMapper\Domain\DomainAbstract $domain)
    {
        return $this->bulkUpdate($this->getBulk()->markForSet($domain));
    }

    /**
     * @return \G4\DataMapper\Mapper\Solr
     */
    private function fetch(\G4\DataMapper\Selection\IdentityAbstract $identity)
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

    private function getResponseStatus()
    {
        return !empty($this->response)
            && isset($this->response["responseHeader"]["status"])
            && $this->response["responseHeader"]["status"] === 0;
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
        //TODO: Drasko: extract to new class!!!
        $transformedData = [];
        if (is_array($this->getRawData()) && count($this->getRawData()) > 0) {
            foreach ($this->getRawData() as $key => $value) {
                $transformedData[empty($value['id']) ? $key : $value['id']] = $value;
            }
        }
        return new \G4\DataMapper\Collection\Content($transformedData, $this->getFactoryDomainName(), $this->getTotalItemsCount());
    }
}