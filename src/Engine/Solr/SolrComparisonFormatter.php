<?php

namespace G4\DataMapper\Engine\Solr;

use G4\DataMapper\Common\ComparisonFormatterInterface;
use G4\DataMapper\Common\Selection\Operator;

class SolrComparisonFormatter implements ComparisonFormatterInterface
{
    private $map = [
        Operator::EQUAL => ':',
    ];

    public function format($name, Operator $operator, $value)
    {
        return sprintf("%s%s%s", $name, $this->operatorMap($operator), $value);
    }

    private function operatorMap(Operator $operator)
    {
        $symbol = $operator->getSymbol();

        if (!isset($this->map[$symbol])) {
            throw new \Exception('Operator not im map', 101);
        }

        return $this->map[$symbol];
    }

}
