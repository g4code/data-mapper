<?php

namespace G4\DataMapper\Engine\Elasticsearch;

use G4\DataMapper\Common\Selection\Comparison;
use G4\DataMapper\Common\SelectionFactoryInterface;
use G4\DataMapper\Common\Selection\Sort;
use G4\DataMapper\Common\IdentityInterface;

class ElasticsearchSelectionFactory implements SelectionFactoryInterface
{
    /**
     * @var IdentityInterface
     */
    private $identity;

    public function __construct(IdentityInterface $identity)
    {
        $this->identity = $identity;
    }

    public function fieldNames()
    {
    }

    public function group()
    {
    }

    public function limit()
    {
    }

    public function offset()
    {
    }

    public function sort()
    {
    }

    public function where()
    {
        if ($this->identity->isVoid())
        {
            return ['must' => ['match_all' => []]];
        }

        $comparisons = [];

        foreach ($this->identity->getComparisons() as $oneComparison) {
            if ($oneComparison instanceof Comparison) {
                $comparisons[] = $oneComparison->getComparison($this->makeComparisonFormatter());
            }
        }

        return ['must' => $comparisons];
    }

    private function makeComparisonFormatter()
    {
        return new ElasticsearchComparisonFormatter();
    }
}
