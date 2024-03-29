<?php

namespace G4\DataMapper\Collection;

use G4\DataMapper\Domain\TotalCount;

class Content extends CollectionAbstract
{
    private $_factoryDomainName;

    // @todo: Dejan, turn _totalItemsCount into class
    private $_totalItemsCount;

    public function __construct(array $rawData = null, $factoryDomainName, $count = null)
    {
        $this->_setRawData($rawData);
        $this->_factoryDomainName = $factoryDomainName;
        $this->_totalItemsCount = (new TotalCount($count))->getValue();
    }

    protected function _factory()
    {
        return $this->factory($this->_getCurrentRawData());
    }

    public function getTotalItemsCount()
    {
        return $this->_totalItemsCount;
    }

    //TODO: Drasko: new object interface!
    public function pickUp($key)
    {
        return isset($this->_rawData[$key])
            ? $this->factory($this->_rawData[$key])
            : null;
    }

    private function factory($data)
    {
        return (new $this->_factoryDomainName())->createObject($data);
    }
}
