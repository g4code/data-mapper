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
use G4\DataMapper\Engine\Elasticsearch\Operators\LikeCIOperator;
use G4\DataMapper\Engine\Elasticsearch\Operators\TimeFromInMinutesOperator;
use G4\DataMapper\Engine\Elasticsearch\Operators\LikeOperator;
use G4\DataMapper\Engine\Elasticsearch\Operators\MissingOperator;
use G4\DataMapper\Engine\Elasticsearch\Operators\ExistsOperator;

class ElasticsearchComparisonFormatter implements ComparisonFormatterInterface
{
    const GREATER_THAN          = 'gt';
    const GREATER_THAN_OR_EQUAL = 'gte';
    const LESS_THAN             = 'lt';
    const LESS_THAN_OR_EQUAL    = 'lte';

    const MATCH                 = 'match';
    const RANGE                 = 'range';
    const TERMS                 = 'terms';

    public function __construct(ElasticsearchIdentity $identity)
    {
        $this->identity = $identity;
    }

    public function format($name, Operator $operator, ValueInterface $value)
    {
        //TODO:Vladan:This should be refactored.
        if (preg_match("/^-/", $name)) {
            $name = ltrim($name, '-');
        }

        switch ($operator->getSymbol()) {
            case Operator::EQUAL:
                $query = new EqualOperator($name, $value);
                break;
            case Operator::EQUAL_CI:
                $query = $this->getOperatorBasedOnEsVersion('EqualCIOperator', $name, $value);
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
            case Operator::TIME_FROM_IN_MINUTES:
                $query = new TimeFromInMinutesOperator($name, $value);
                break;
            case Operator::LIKE:
                $query = new LikeOperator($name, $value);
                break;
            case Operator::LIKE_CI:
                $query = new LikeCIOperator($name, $value);
                break;
            case Operator::MISSING:
                $query = new MissingOperator($name, $value);
                break;
            case Operator::EXISTS:
                $query = new ExistsOperator($name, $value);
                break;
        }

        return $query->format();
    }

    private function getOperatorBasedOnEsVersion($operatorName, $name, $value)
    {
        $version = $this->identity->getVersion();
        $versionedPath = "G4\\DataMapper\\Engine\\Elasticsearch\\Version$version\\Operators\\$operatorName";
        if (class_exists($versionedPath)) {
            return new $versionedPath($name, $value);
        }

        $defaultPath = "G4\\DataMapper\\Engine\\Elasticsearch\\Operators\\$operatorName";
        return new $defaultPath($name, $value);
    }
}
