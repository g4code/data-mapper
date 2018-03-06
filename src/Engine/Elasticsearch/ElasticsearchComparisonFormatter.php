<?php

namespace G4\DataMapper\Engine\Elasticsearch;

use G4\DataMapper\Common\ComparisonFormatterInterface;
use G4\DataMapper\Common\Selection\Operator;
use G4\DataMapper\Common\ValueInterface;
use G4\DataMapper\Engine\Elasticsearch\Operators\BetweenOperator;
use G4\DataMapper\Engine\Elasticsearch\Operators\EqualOperator;
use G4\DataMapper\Engine\Elasticsearch\Operators\GreaterThanOperator;
use G4\DataMapper\Engine\Elasticsearch\Operators\GreaterThanOrEqualOperator;
use G4\DataMapper\Engine\Elasticsearch\Operators\InOperator;
use G4\DataMapper\Engine\Elasticsearch\Operators\LessThanOperator;
use G4\DataMapper\Engine\Elasticsearch\Operators\LessThanOrEqualOperator;

class ElasticsearchComparisonFormatter implements ComparisonFormatterInterface
{
    const GREATER_THAN          = 'gt';
    const GREATER_THAN_OR_EQUAL = 'gte';
    const LESS_THAN             = 'lt';
    const LESS_THAN_OR_EQUAL    = 'lte';

    const MATCH                 = 'match';
    const RANGE                 = 'range';
    const TERMS                 = 'terms';

    public function format($name, Operator $operator, ValueInterface $value)
    {
        switch ($operator->getSymbol()) {
            case Operator::EQUAL:
                $query = new EqualOperator($name, $value);
                break;
            case Operator::GRATER_THAN:
                $query = new GreaterThanOperator($name, $value);
                break;
            case Operator::LESS_THAN:
                $query = new LessThanOperator($name, $value);
                break;
            case Operator::GRATER_THAN_OR_EQUAL:
                $query = new GreaterThanOrEqualOperator($name, $value);
                break;
            case Operator::LESS_THAN_OR_EQUAL:
                $query = new LessThanOrEqualOperator($name, $value);
                break;
            case Operator::IN:
                $query = new InOperator($name, $value);
                break;
            case Operator::BETWEEN:
                $query = new BetweenOperator($name, $value);
                break;
        }

        return $query->format();
    }
}
