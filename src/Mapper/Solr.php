<?php

namespace G4\DataMapper\Mapper;

class Solr
{

    /**
     * @var G4\DataMapper\Selection\Solr\Factory
     */
    private $selectionFactory;

    /**
     * @var string
     */
    private $selectUrl;

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
        $this->selectUrl       = $selectUrl;
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

    public function getFactoryDomainName()
    {
        return '\Api\Model\Factory\Domain\User\User';
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

    private function fetch(\G4\DataMapper\Selection\Identity $identity)
    {
        $url = $this->getSelectUrl($identity);
        $ch  = curl_init($url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array('q' => $this->getSelectionFactory()->query($identity)));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $output = curl_exec($ch);

        curl_close($ch);

        $resultFeed = json_decode($output, true);

        $this->totalItemsCount = empty($resultFeed['response']['numFound'])
            ? 0
            : $resultFeed['response']['numFound'];
        $this->rawData         = empty($resultFeed["response"]["docs"])
            ? array()
            : $resultFeed["response"]["docs"];

        return $this;
    }

    private function getSelectUrl(\G4\DataMapper\Selection\Identity $identity)
    {
        return $this->selectUrl . '?' . http_build_query($this->getSelectionFactory()->requestParams($identity));
    }
}