<?php

namespace G4\DataMapper\Collection;

class CollectionFactory
{
    public static function create(array $rawData, $factoryDomainName, int $total): Content
    {
        return new Content($rawData, $factoryDomainName, $total);
    }

    public static function createEmpty($factoryDomainName): Content
    {
        return new Content(null, $factoryDomainName, 0);
    }
}
