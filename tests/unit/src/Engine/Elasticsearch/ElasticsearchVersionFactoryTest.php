<?php

namespace unit\src\Engine\Elasticsearch;

use G4\DataMapper\Engine\Elasticsearch\ElasticsearchVersionFactory;
use PHPUnit_Framework_TestCase;

class ElasticsearchVersionFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testElasticsearchClientUrlPath()
    {
        $classPath = ElasticsearchVersionFactory::getVersionedClassPath('ElasticsearchClientUrlPath', 2);
        self::assertEquals('G4\DataMapper\Engine\Elasticsearch\ElasticsearchClientUrlPath', $classPath);

        $classPath = ElasticsearchVersionFactory::getVersionedClassPath('ElasticsearchClientUrlPath', 7);
        self::assertEquals('G4\DataMapper\Engine\Elasticsearch\ElasticsearchClientUrlPath', $classPath);

        $classPath = ElasticsearchVersionFactory::getVersionedClassPath('ElasticsearchClientUrlPath', 8);
        self::assertEquals('G4\DataMapper\Engine\Elasticsearch\Version8\ElasticsearchClientUrlPath', $classPath);
    }

    /** @dataProvider operatorData */
    public function testOperatorGetVersionedClassPath($operatorName, $version, $expectedResult)
    {
        $classPath = ElasticsearchVersionFactory::getVersionedClassPath(
            "Operators\\$operatorName",
            $version
        );

        self::assertEquals($expectedResult, $classPath);
    }

    public function operatorData()
    {
        return [
            ['BetweenOperator', 2, 'G4\DataMapper\Engine\Elasticsearch\Operators\BetweenOperator'],
            ['ConsistentRandomKey', 2, 'G4\DataMapper\Engine\Elasticsearch\Operators\ConsistentRandomKey'],
            ['EqualCIOperator', 2, 'G4\DataMapper\Engine\Elasticsearch\Operators\EqualCIOperator'],
            ['EqualOperator', 2, 'G4\DataMapper\Engine\Elasticsearch\Operators\EqualOperator'],
            ['ExistsOperator', 2, 'G4\DataMapper\Engine\Elasticsearch\Operators\ExistsOperator'],
            ['GreaterThanOperator', 2, 'G4\DataMapper\Engine\Elasticsearch\Operators\GreaterThanOperator'],
            ['GreaterThanOrEqualOperator', 2, 'G4\DataMapper\Engine\Elasticsearch\Operators\GreaterThanOrEqualOperator'],
            ['InOperator', 2, 'G4\DataMapper\Engine\Elasticsearch\Operators\InOperator'],
            ['InOperator', 2, 'G4\DataMapper\Engine\Elasticsearch\Operators\InOperator'],
            ['LessThanOrEqualOperator', 2, 'G4\DataMapper\Engine\Elasticsearch\Operators\LessThanOrEqualOperator'],
            ['LikeCIOperator', 2, 'G4\DataMapper\Engine\Elasticsearch\Operators\LikeCIOperator'],
            ['LikeOperator', 2, 'G4\DataMapper\Engine\Elasticsearch\Operators\LikeOperator'],
            ['MissingOperator', 2, 'G4\DataMapper\Engine\Elasticsearch\Operators\MissingOperator'],
            ['QueryStringOperator', 2, 'G4\DataMapper\Engine\Elasticsearch\Operators\QueryStringOperator'],
            ['TimeFromInMinutesOperator', 2, 'G4\DataMapper\Engine\Elasticsearch\Operators\TimeFromInMinutesOperator'],

            ['BetweenOperator', 7, 'G4\DataMapper\Engine\Elasticsearch\Operators\BetweenOperator'],
            ['ConsistentRandomKey', 7, 'G4\DataMapper\Engine\Elasticsearch\Version7\Operators\ConsistentRandomKey'],
            ['EqualCIOperator', 7, 'G4\DataMapper\Engine\Elasticsearch\Version7\Operators\EqualCIOperator'],
            ['EqualOperator', 7, 'G4\DataMapper\Engine\Elasticsearch\Operators\EqualOperator'],
            ['ExistsOperator', 7, 'G4\DataMapper\Engine\Elasticsearch\Operators\ExistsOperator'],
            ['GreaterThanOperator', 7, 'G4\DataMapper\Engine\Elasticsearch\Operators\GreaterThanOperator'],
            ['GreaterThanOrEqualOperator', 7, 'G4\DataMapper\Engine\Elasticsearch\Operators\GreaterThanOrEqualOperator'],
            ['InOperator', 7, 'G4\DataMapper\Engine\Elasticsearch\Operators\InOperator'],
            ['InOperator', 7, 'G4\DataMapper\Engine\Elasticsearch\Operators\InOperator'],
            ['LessThanOrEqualOperator', 7, 'G4\DataMapper\Engine\Elasticsearch\Operators\LessThanOrEqualOperator'],
            ['LikeCIOperator', 7, 'G4\DataMapper\Engine\Elasticsearch\Version7\Operators\LikeCIOperator'],
            ['LikeOperator', 7, 'G4\DataMapper\Engine\Elasticsearch\Operators\LikeOperator'],
            ['MissingOperator', 7, 'G4\DataMapper\Engine\Elasticsearch\Operators\MissingOperator'],
            ['QueryStringOperator', 7, 'G4\DataMapper\Engine\Elasticsearch\Version7\Operators\QueryStringOperator'],
            ['TimeFromInMinutesOperator', 7, 'G4\DataMapper\Engine\Elasticsearch\Operators\TimeFromInMinutesOperator'],
        ];
    }
}
