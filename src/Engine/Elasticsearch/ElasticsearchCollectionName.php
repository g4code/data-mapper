<?php

namespace G4\DataMapper\Engine\Elasticsearch;

use G4\DataMapper\Common\CollectionNameInterface;
use G4\DataMapper\Exception\NotImplementedException;

class ElasticsearchCollectionName implements CollectionNameInterface
{

    /**
     * @var ElasticsearchIndexName
     */
    private $indexName;

    /**
     * @var ElasticsearchTypeName
     */
    private $typeName;


    public function __construct(ElasticsearchIndexName $indexName, ElasticsearchTypeName $typeName)
    {
        $this->indexName = $indexName;
        $this->typeName  = $typeName;
    }

    public function __toString()
    {
        return '';
    }

    public function getIndexName()
    {
        return $this->indexName;
    }

    public function getTypeName()
    {
        return $this->typeName;
    }
}