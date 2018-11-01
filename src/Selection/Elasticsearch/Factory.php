<?php

namespace G4\DataMapper\Selection\Elasticsearch;

use G4\DataMapper\Selection\Elasticsearch\Identity;
use G4\DataMapper\Selection\Elasticsearch\Consts;
use G4\DataMapper\Selection\IdentityInterface;

//TODO: Drasko: This needs refactoring!!!
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
            'body' => [
                $this->typeName => $this->mappings,
            ],
        ];
    }

    public function prepareForIndexing()
    {
        return $this->prepareType() + [
            'id'   => $this->id,
            'body' => $this->body,
        ];
    }

    public function prepareForUpdate()
    {
        return $this->prepareType() + [
            'id'   => $this->id,
            'body' => [
                'doc' => $this->body,
            ],
        ];
    }

    public function prepareForUpdateAppend()
    {
        return $this->prepareType() + [
            'id'   => $this->id,
            'body' => [
                'script' => 'ctx._source.' . key($this->body) . ' += data',
                'params' => [
                    'data' => current($this->body),
                ],
                'upsert' => [
                    key($this->body) => []
                ]
            ],
        ];
    }

    public function prepareIndex()
    {
        return [
            'index' => $this->indexName,
        ];
    }

    public function prepareId()
    {
        return $this->prepareType() + [
            'id' => $this->id,
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
                if ($comp['name'] === 'geo_distance') {
                    $filters[$comp['operator']][][$this->getTerm($comp['value'])] = $comp['value'];
                } else {
                    $filters[$comp['operator']][][$this->getTerm($comp['value'])][$comp['name']] = $comp['value'];
                }
            }
        }

        if (empty($queries)) {
            $queries = [
                'match_all' => [],
            ];
        }

        $result = $this->prepareType() + [
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
                'sort' => $this->orderBy(),
            ]
        ];

        $groupBy = $this->groupBy();
        if (count($groupBy) > 0) {
            $result['body']['aggs'] = $groupBy;
            $result['size'] = 0;
        }

        return $result;
    }

    //TODO: Drasko: This needs refactoring!!!
    public function orderBy(IdentityInterface $identity = null)
    {
        if (is_null($this->identity) || !$this->identity->hasOrderBy()) {
            return [];
        }
        $sort = [];
        foreach ($this->identity->getOrderBy() as $key => $value) {
            if (strpos($key, 'random') !== false) {
                $sort["_script"] = [
                    "type"   => "number",
                    "script" => "Math.random()",
                    "order"  => "asc"
                ];
            } elseif ($key === Consts::GEO_DISTANCE_SORT) {
                $sort[$key] = $value;
            } elseif ($key !== null) {
                $sort[$key] = (strtolower($value) == Consts::DESCENDING)
                    ? \G4\DataMapper\Selection\Solr\Consts\Query::DESCENDING
                    : \G4\DataMapper\Selection\Solr\Consts\Query::ASCENDING;
            }
        }
        return $sort;
    }

    private function getTerm($value)
    {
        $term = Consts::TERM;
        if (is_array($value)) {
            $term = Consts::TERMS;
        }
        if (is_array($value) && count(array_intersect(Consts::rangeParams(), array_keys($value))) > 0) {
            $term = Consts::RANGE;
        }
        if (is_array($value) && count(array_intersect(Consts::geoParams(), array_keys($value))) > 0) {
            $term = Consts::GEO_DISTANCE;
        }
        return $term;
    }

    private function groupBy()
    {
        if (is_null($this->identity) || !$this->identity->hasGroupBy()) {
            return [];
        }
        return [
            'group_by' => [
                'terms' => [
                    'field' => $this->identity->getGroupBy(),
                    'size' => $this->identity->getLimit(),
                ],
                'aggs' => [
                    'group_by_hits' => [
                        'top_hits' => [
                            'sort' => [
                                'id' => [
                                    'order' => 'desc',
                                ],
                            ],
                            'size' => 1,
                        ],
                    ],
                ],
            ],
        ];
    }
}
