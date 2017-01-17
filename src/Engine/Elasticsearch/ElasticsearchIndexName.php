<?php

namespace G4\DataMapper\Engine\Elasticsearch;

use G4\DataMapper\Common\CollectionNameInterface;
use G4\DataMapper\Exception\IndexNameException;
use G4\DataMapper\Exception\TypeNameException;

class ElasticsearchIndexName implements CollectionNameInterface
{

    /**
     * @var string
     */
    private $indexName;

    /**
     * ElasticsearchIndexName constructor.
     * @param $indexName
     */
    public function __construct($indexName)
    {
        if (!is_string($indexName) || strlen($indexName) === 0) {
            throw new IndexNameException();
        }
        $this->indexName = $indexName;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->indexName;
    }
}