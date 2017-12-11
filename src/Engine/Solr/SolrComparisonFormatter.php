<?php

namespace G4\DataMapper\Engine\Solr;

use G4\DataMapper\Common\ComparisonFormatterInterface;
use G4\DataMapper\Common\Selection\Operator;

class SolrComparisonFormatter implements ComparisonFormatterInterface
{

    public function format($name, Operator $operator, $value)
    {
        if($operator->getSymbol() === Operator::EQUAL) {
            return $name . ':' . $value;
        } elseif ($operator->getSymbol() === Operator::GRATER_THAN) {
            return $name . ':' . '{' . $value . ' TO ' . '*' . '}';
        }
    }
}
