<?php

namespace G4\DataMapper\Engine\Elasticsearch;

use G4\DataMapper\Common\CollectionNameInterface;
use G4\DataMapper\Exception\CollectionNameException;

class ElasticsearchCollectionName implements CollectionNameInterface
{

    /**
     * @var string
     */
    private $collectionName;

    /**
     * SolrCollectionName constructor.
     * @param $collectionName
     * @throws CollectionNameException
     */
    public function __construct($collectionName)
    {
        if (!is_string($collectionName) || strlen($collectionName) === 0) {
            throw new CollectionNameException();
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
