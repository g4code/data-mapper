<?php

namespace G4\DataMapper\Engine\Solr;

use G4\DataMapper\Common\CollectionNameInterface;
use G4\DataMapper\Exception\TableNameException;

class SolrCollectionName implements CollectionNameInterface
{
    /**
     * @var string
     */
    private $collectionName;

    /**
     * SolrCollectionName constructor.
     * @param $collectionName
     * @throws TableNameException
     */
    public function __construct($collectionName)
    {
        if(!is_string($collectionName) || strlen($collectionName) === 0) {
            throw new TableNameException();
        }
        $this->collectionName = $collectionName;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->collectionName;
    }
}