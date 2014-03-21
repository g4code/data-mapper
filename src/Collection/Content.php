<?php

namespace G4\DataMapper\Collection;

class Content extends CollectionAbstract
{
    private $_factoryDomainName;

    // @todo: Dejan, turn _totalItemsCount into class
    private $_totalItemsCount;

    public function __construct(array $rawData = null, $factoryDomainName, $count = null)
    {
        $this->_setRawData($rawData);
        $this->_factoryDomainName = $factoryDomainName;
        $this->_totalItemsCount   = intval($count);
    }

    protected function _factory()
    {
        $factoryDomain = new $this->_factoryDomainName();
        return $factoryDomain->createObject($this->_getCurrentRawData());
    }

    public function getTotalItemsCount()
    {
        return $this->_totalItemsCount;
    }
}