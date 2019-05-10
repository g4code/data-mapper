<?php

namespace G4\DataMapper\Engine\Elasticsearch;

use G4\DataMapper\Common\Selection\Comparison;
use G4\DataMapper\Common\SelectionFactoryInterface;
use G4\DataMapper\Common\Selection\Sort;

class ElasticsearchSelectionFactory implements SelectionFactoryInterface
{
    /**
     * @var ElasticsearchIdentityInterface
     */
    private $identity;

    public function __construct(ElasticsearchIdentityInterface $identity)
    {
        $this->identity = $identity;
    }

    public function fieldNames()
    {
        $fieldNames = $this->identity->getFieldNames();

        if (!is_array($fieldNames)) {
            return [];
        }

        return count($fieldNames) === 0
            ? []
            : $fieldNames;
    }

    public function group()
    {
        return [
            'group_by_' . $this->identity->getGrouping() => [
                'terms' => [
                    'field' => $this->identity->getGrouping()
                ]
            ]
        ];
    }

    public function limit()
    {
        return (int) $this->identity->getLimit();
    }

    public function offset()
    {
        return (int) $this->identity->getOffset();
    }

    public function sort()
    {
        $rawSorting = $this->identity->getSorting();

        if (empty($rawSorting)) {
            return [];
        }

        $sorting = [];

        foreach ($rawSorting as $oneSort) {
            if ($oneSort instanceof Sort) {
                $sorting[] = $oneSort->getSort($this->makeSortingFormatter($oneSort));
            }
        }

        return $sorting;
    }

    //TODO:Vladan:It is too complicated.
    public function where()
    {
        if ($this->identity->isVoid()) {
            return $this->addConsistentRandomKey(['bool' => ['must' => ['match_all' => []]]]);
        }

        $comparisons = [];

        foreach ($this->identity->getComparisons() as $oneComparison) {
            if ($oneComparison instanceof Comparison && !$oneComparison->getValue()->isEmpty()) {
                if (preg_match("/^-/", $oneComparison->getName())) {
                    $comparisons['must_not'][]= $oneComparison->getComparison($this->makeComparisonFormatter());
                } else {
                    $comparisons['must'][]= $oneComparison->getComparison($this->makeComparisonFormatter());
                }
            }
        }

        if ($this->identity->hasRawQuery()) {
            $comparisons['must'][]= $this->identity->getRawQuery();
        }

        if (empty($comparisons)) {
            $comparisons =  ['must' => ['match_all' => []]];
        }

        $comparisons['filter'] = (new ElasticsearchGeodistFormatter($this->identity))->format();

        return $this->addConsistentRandomKey(['bool' => $comparisons]);
    }

    /**
     * @param array $data
     * @return array
     */
    private function addConsistentRandomKey(array $data)
    {
        return $this->identity->hasConsistentRandomKey()
            ? [
                'function_score' => [
                    'query' => $data,
                    'random_score' => [
                        'seed' => $this->identity->getConsistentRandomKey()
                    ]
                ]
            ]
            : $data;
    }

    private function makeComparisonFormatter()
    {
        return new ElasticsearchComparisonFormatter();
    }

    /**
     * @param Sort $sortData
     * @return ElasticsearchGeodistSortFormatter|ElasticsearchSortingFormatter
     */
    private function makeSortingFormatter(Sort $sortData)
    {
        return $sortData instanceof ElasticsearchGeodistSort
            ? new ElasticsearchGeodistSortFormatter($this->identity)
            : new ElasticsearchSortingFormatter();
    }
}
