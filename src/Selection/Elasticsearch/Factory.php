<?php

namespace G4\DataMapper\Selection\Elasticsearch;

use G4\DataMapper\Selection\Elasticsearch\Identity;
use G4\DataMapper\Selection\Elasticsearch\Consts;

class Factory extends \G4\DataMapper\Selection\Factory
{

    private $body;

    private $id;

    /**
     * @var Identity
     */
    private $identity;

    /**
     * @var string
     */
    private $indexName;

    /**
     * @var array
     */
    private $mappings;

    /**
     * @var string
     */
    private $typeName;


    public function prepareForMapping()
    {
        return $this->prepareType() + [
            'body' => $this->mappings,
        ];
    }

    public function prepareForIndexing()
    {
        return $this->prepareType() + [
            'id'   => $this->id,
            'body' => $this->body,
        ];
    }


    public function prepareIndex()
    {
        return [
            'index' => $this->indexName,
        ];
    }

    public function prepareType()
    {
        return $this->prepareIndex() + [
            'type' => $this->typeName,
        ];
    }

    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setIdentity(Identity $identity = null)
    {
        $this->identity = $identity;
        return $this;
    }


    public function setIndexName($value)
    {
        $this->indexName = $value;
        return $this;
    }

    public function setMappings(array $mappings)
    {
        $this->mappings = $mappings;
        return $this;
    }

    public function setTypeName($value)
    {
        $this->typeName = $value;
        return $this;
    }


    //TODO: Drasko: This needs refactoring!!!
    public function query()
    {
        $filters = [];
        $queries = [];
        foreach ($this->identity->getComps() as $comp) {
            if ($comp['value'] === null || (is_array($comp['value']) && empty($comp['value']))) {
                continue;
            }
            if ($comp['operator'] === Consts::WILDCARD) {
                $queries['bool']['must'][][Consts::WILDCARD][$comp['name']] = $comp['value'];
            } else {
                $termFilter = is_array($comp['value']) ? Consts::TERMS : Consts::TERM;
                $filters[$comp['operator']][][$termFilter][$comp['name']] = $comp['value'];
            }
        }
        if (empty($queries)) {
            $queries = [
                'match_all' => [],
            ];
        }
        return $this->prepareType() + [
            'from'  => $this->offset($this->identity),
            'size'  => $this->limit($this->identity),
            'body'  => [
                'query' => [
                    'filtered' => [
                        'query' => $queries,
                        'filter' => [
                            'bool' => $filters,
                        ],
                    ],
                ],
            ]
        ];
    }
}