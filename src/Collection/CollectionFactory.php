<?php

namespace G4\DataMapper\Collection;

class CollectionFactory
{
    /**
     * @param array $rawData
     * @param $factoryDomainName
     * @param int $total
     * @return Content
     */
    public static function create(array $rawData, $factoryDomainName, int $total)
    {
        return new Content($rawData, $factoryDomainName, $total);
    }

    /**
     * @param $factoryDomainName
     * @return Content
     */
    public static function createEmpty($factoryDomainName)
    {
        return new Content(null, $factoryDomainName, 0);
    }
}
