<?php

namespace G4\DataMapper\Engine\Elasticsearch;

use G4\DataMapper\Common\CollectionNameInterface;
use G4\DataMapper\Exception\IndexNameException;
use G4\DataMapper\Exception\TypeNameException;

class ElasticsearchTypeName implements CollectionNameInterface
{

    /**
     * @var string
     */
    private $typeName;

    /**
     * ElasticsearchIndexAndType constructor.
     * @param $typeName
     */
    public function __construct($typeName)
    {
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
        return $this->typeName;
    }
}
