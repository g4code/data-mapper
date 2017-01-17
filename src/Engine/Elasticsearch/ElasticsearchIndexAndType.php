<?php

namespace G4\DataMapper\Engine\Elasticsearch;

use G4\DataMapper\Common\CollectionNameInterface;
use G4\DataMapper\Exception\IndexNameException;
use G4\DataMapper\Exception\TypeNameException;

class ElasticsearchIndexAndType implements CollectionNameInterface
{

    /**
     * @var string
     */
    private $indexName;

    /**
     * @var string
     */
    private $typeName;

    /**
     * ElasticsearchIndexAndType constructor.
     * @param $indexName
     * @param $typeName
     */
    public function __construct($indexName, $typeName)
    {
        if (!is_string($indexName) || strlen($indexName) === 0) {
            throw new IndexNameException();
        }
        $this->indexName = $indexName;

        if (!is_string($typeName) || strlen($typeName) === 0) {
            throw new TypeNameException();
        }
        $this->typeName = $typeName;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->indexName;
    }

    /**
     * @return string
     */
    public function getIndexName()
    {
        return $this->indexName;
    }

    /**
     * @return string
     */
    public function getTypeName()
    {
        return $this->typeName;
    }
}