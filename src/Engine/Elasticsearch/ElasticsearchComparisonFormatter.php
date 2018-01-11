<?php

namespace G4\DataMapper\Engine\Elasticsearch;

use G4\DataMapper\Common\ComparisonFormatterInterface;
use G4\DataMapper\Common\Selection\Operator;

class ElasticsearchComparisonFormatter implements ComparisonFormatterInterface
{
    const GREATER_THAN          = 'gt';
    const GREATER_THAN_OR_EQUAL = 'gte';
    const LESS_THAN             = 'lt';
    const LESS_THAN_OR_EQUAL    = 'lte';

    const MATCH                 = 'match';
    const RANGE                 = 'range';
    const TERMS                 = 'terms';

    public function format($name, Operator $operator, $value)
    {
        switch($operator->getSymbol()) {
            case Operator::EQUAL:
                $query = $this->formatEqualQuery($name, $value);
                break;
            case Operator::GRATER_THAN:
                $query = $this->formatGreaterThanQuery($name, $value);
                break;
            case Operator::LESS_THAN:
                $query = $this->formatLessThanQuery($name, $value);
                break;
            case Operator::GRATER_THAN_OR_EQUAL:
                $query = $this->formatGreaterThanOrEqualQuery($name, $value);
                break;
            case Operator::LESS_THAN_OR_EQUAL:
                $query = $this->formatLessThanOrEqualQuery($name, $value);
                break;
            case Operator::IN:
                $query = $this->formatInQuery($name, $value);
                break;
        }

        return $query;
    }


    private function formatEqualQuery($name, $value)
    {
        return [self::MATCH => [$name => $value]];
    }

    private function formatGreaterThanQuery($name, $value)
    {
        return [self::RANGE => [$name => [self::GREATER_THAN => $value]]];
    }

    private function formatLessThanQuery($name, $value)
    {
        return [self::RANGE => [$name => [self::LESS_THAN => $value]]];
    }

    private function formatGreaterThanOrEqualQuery($name, $value)
    {
        return [self::RANGE => [$name => [self::GREATER_THAN_OR_EQUAL => $value]]];
    }

    private function formatLessThanOrEqualQuery($name, $value)
    {
        return [self::RANGE => [$name => [self::LESS_THAN_OR_EQUAL => $value]]];
    }

    private function formatInQuery($name, $value)
    {
        return [self::TERMS => [$name => explode(',', $value)]];
    }
}
