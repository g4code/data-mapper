<?php

namespace G4\DataMapper\Engine\Solr;

use G4\DataMapper\Common\ComparisonFormatterInterface;
use G4\DataMapper\Common\Selection\Operator;
use G4\DataMapper\Common\ValueInterface;
use G4\DataMapper\Engine\Solr\Operators\BetweenQueryOperator;
use G4\DataMapper\Engine\Solr\Operators\EqualQueryOperator;
use G4\DataMapper\Engine\Solr\Operators\GeodistOperator;
use G4\DataMapper\Engine\Solr\Operators\GreaterThanOperator;
use G4\DataMapper\Engine\Solr\Operators\GreaterThanOrEqualOperator;
use G4\DataMapper\Engine\Solr\Operators\InOperator;
use G4\DataMapper\Engine\Solr\Operators\LessThanOperator;
use G4\DataMapper\Engine\Solr\Operators\LessThanOrEqualOperator;
use G4\DataMapper\Engine\Solr\Operators\LikeOperator;
use G4\DataMapper\Engine\Solr\Operators\TimeFromInMinutes;

class SolrComparisonFormatter implements ComparisonFormatterInterface
{
    public function format($name, Operator $operator, ValueInterface $value)
    {
        switch ($operator->getSymbol()) {
            case Operator::EQUAL:
                $query = new EqualQueryOperator($name, $value);
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
            case Operator::LIKE:
                $query = new LikeOperator($name, $value);
                break;
            case Operator::BETWEEN:
                $query = new BetweenQueryOperator($name, $value);
                break;
            case Operator::TIME_FROM_IN_MINUTES:
                $query = new TimeFromInMinutes($name, $value);
                break;
        }

        return $query->format();
    }
}
